<?php

namespace Tests\Feature;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Models\Branch;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Livewire\Livewire;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_assistant_lists_only_published_branches_and_supports_location_coordinates(): void
    {
        Branch::factory()->create([
            'name' => 'Visible Branch',
            'latitude' => 14.5995,
            'longitude' => 120.9842,
            'is_published' => true,
        ]);
        Branch::factory()->create(['name' => 'Hidden Branch', 'is_published' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Ask Armour')
            ->assertSee('Visible Branch')
            ->assertDontSee('Hidden Branch')
            ->assertSee('data-find-nearest', false)
            ->assertSee('inquiry-branch-option', false)
            ->assertSee('type="radio" name="branch_id"', false)
            ->assertSee('data-latitude="14.5995000"', false);
    }

    public function test_visitor_can_submit_an_inquiry_and_phone_number_is_normalized(): void
    {
        $branch = Branch::factory()->create(['name' => 'Armour Quezon City']);

        $this->withServerVariables(['REMOTE_ADDR' => '10.10.0.1'])
            ->postJson(route('inquiries.store'), $this->validPayload($branch, ['contact' => '0912 345-6789']))
            ->assertCreated()
            ->assertJsonPath('message', 'Thanks! Your inquiry was sent to Armour Quezon City. Our team will contact you using the number provided.');

        $this->assertDatabaseHas('inquiries', [
            'branch_id' => $branch->id,
            'branch_name' => 'Armour Quezon City',
            'name' => 'Juan Dela Cruz',
            'contact' => '09123456789',
            'interest' => 'products',
            'status' => 'new',
        ]);
    }

    public function test_public_inquiry_rejects_honeypots_invalid_contacts_and_unpublished_branches(): void
    {
        $publishedBranch = Branch::factory()->create();
        $hiddenBranch = Branch::factory()->create(['is_published' => false]);

        $this->withServerVariables(['REMOTE_ADDR' => '10.10.0.2'])
            ->postJson(route('inquiries.store'), $this->validPayload($publishedBranch, [
                'contact' => 'https://spam.example',
                'website' => 'https://spam.example',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['contact', 'website']);

        $this->withServerVariables(['REMOTE_ADDR' => '10.10.0.3'])
            ->postJson(route('inquiries.store'), $this->validPayload($hiddenBranch))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('branch_id');

        $this->withServerVariables(['REMOTE_ADDR' => '10.10.0.6'])
            ->postJson(route('inquiries.store'), $this->validPayload($publishedBranch, [
                'name' => '<script>alert(1)</script>',
                'form_token' => 'invalid-token',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'form_token']);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_recent_duplicate_inquiries_are_stored_once(): void
    {
        $branch = Branch::factory()->create();
        $payload = $this->validPayload($branch);

        $this->withServerVariables(['REMOTE_ADDR' => '10.10.0.4'])->postJson(route('inquiries.store'), $payload)->assertCreated();
        $this->withServerVariables(['REMOTE_ADDR' => '10.10.0.4'])->postJson(route('inquiries.store'), $payload)->assertOk();

        $this->assertDatabaseCount('inquiries', 1);
    }

    public function test_inquiry_endpoint_is_rate_limited(): void
    {
        $branch = Branch::factory()->create();
        $payload = $this->validPayload($branch);

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.10.0.5'])->postJson(route('inquiries.store'), $payload)->assertSuccessful();
        }

        $this->withServerVariables(['REMOTE_ADDR' => '10.10.0.5'])
            ->postJson(route('inquiries.store'), $payload)
            ->assertTooManyRequests();
    }

    public function test_oversized_inquiry_payloads_are_rejected_early(): void
    {
        $branch = Branch::factory()->create();

        $this->withServerVariables([
            'REMOTE_ADDR' => '10.10.0.7',
            'CONTENT_LENGTH' => '20000',
        ])->postJson(route('inquiries.store'), $this->validPayload($branch))->assertStatus(413);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_administrators_can_manage_inquiries_in_filament(): void
    {
        $inquiry = Inquiry::factory()->create(['branch_name' => 'Armour Test Branch']);
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->get(InquiryResource::getUrl())->assertOk();
        $this->get(InquiryResource::getUrl('edit', ['record' => $inquiry]))->assertOk();

        Livewire::test(ListInquiries::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$inquiry])
            ->assertTableColumnExists('status')
            ->assertTableColumnExists('branch_name')
            ->assertTableFilterExists('status')
            ->assertTableFilterExists('branch_id')
            ->assertTableFilterExists('interest')
            ->assertTableFilterExists('created_at')
            ->assertTableActionExists('delete');
    }

    /** @param array<string, mixed> $overrides */
    private function validPayload(Branch $branch, array $overrides = []): array
    {
        return array_merge([
            'branch_id' => $branch->id,
            'interest' => 'products',
            'name' => 'Juan Dela Cruz',
            'contact' => '09123456789',
            'website' => '',
            'form_token' => Crypt::encryptString((string) now()->subSeconds(3)->timestamp),
        ], $overrides);
    }
}
