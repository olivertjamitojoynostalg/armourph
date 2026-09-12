<button class="inquiry-launcher" type="button" aria-expanded="false" aria-controls="inquiry-assistant">
    <span aria-hidden="true">✦</span>
    <strong>Ask Armour</strong>
</button>

<aside class="inquiry-assistant" id="inquiry-assistant" aria-hidden="true" aria-label="Armour inquiry assistant">
    <header class="inquiry-assistant-header">
        <div><span>Armour assistant</span><strong>How can we help?</strong></div>
        <button type="button" data-inquiry-close aria-label="Close inquiry assistant">×</button>
    </header>

    <div class="inquiry-assistant-body">
        <p class="inquiry-bubble">I’ll help route your inquiry to the most convenient Armour branch.</p>

        @if ($inquiryBranches->isNotEmpty())
            <form method="POST" action="{{ route('inquiries.store') }}" data-inquiry-form novalidate>
                @csrf
                <input type="hidden" name="form_token" value="{{ $inquiryFormToken }}">
                <div class="inquiry-honeypot" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                <div class="inquiry-step active" data-inquiry-step="1">
                    <p class="inquiry-step-count">Step 1 of 4</p>
                    <h2>Which branch is nearest?</h2>
                    <button class="inquiry-location-button" type="button" data-find-nearest>Use my current location</button>
                    <p class="inquiry-location-status" data-location-status aria-live="polite">Your browser will ask permission before sharing your location.</p>
                    <fieldset class="inquiry-branch-field">
                        <legend>Or choose a branch</legend>
                        <div class="inquiry-branch-options">
                            @foreach ($inquiryBranches as $branch)
                                <label class="inquiry-branch-option">
                                    <input type="radio" name="branch_id" value="{{ $branch->id }}" data-latitude="{{ $branch->latitude }}" data-longitude="{{ $branch->longitude }}" required>
                                    <span class="inquiry-branch-option-content">
                                        <span><strong>{{ $branch->name }}</strong><small>{{ $branch->address }}</small></span>
                                        <span class="inquiry-branch-check" aria-hidden="true">✓</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                    <button class="inquiry-primary" type="button" data-inquiry-next>Continue</button>
                </div>

                <div class="inquiry-step" data-inquiry-step="2" hidden>
                    <p class="inquiry-step-count">Step 2 of 4</p>
                    <h2>What can we help with?</h2>
                    <div class="inquiry-options">
                        @foreach (['products' => 'Product inquiry', 'packages' => 'Package inquiry', 'installation' => 'Installation', 'general' => 'General inquiry'] as $value => $label)
                            <label><input type="radio" name="interest" value="{{ $value }}" required><span>{{ $label }}</span></label>
                        @endforeach
                    </div>
                    <div class="inquiry-step-actions"><button type="button" data-inquiry-back>Back</button><button class="inquiry-primary" type="button" data-inquiry-next>Continue</button></div>
                </div>

                <div class="inquiry-step" data-inquiry-step="3" hidden>
                    <p class="inquiry-step-count">Step 3 of 4</p>
                    <h2>What is your name?</h2>
                    <label class="inquiry-field"><span>Full name</span><input type="text" name="name" minlength="2" maxlength="100" autocomplete="name" required placeholder="Juan Dela Cruz"></label>
                    <div class="inquiry-step-actions"><button type="button" data-inquiry-back>Back</button><button class="inquiry-primary" type="button" data-inquiry-next>Continue</button></div>
                </div>

                <div class="inquiry-step" data-inquiry-step="4" hidden>
                    <p class="inquiry-step-count">Step 4 of 4</p>
                    <h2>How can the branch contact you?</h2>
                    <label class="inquiry-field"><span>Mobile number</span><input type="tel" name="contact" maxlength="21" autocomplete="tel" required placeholder="09XX XXX XXXX"></label>
                    <p class="inquiry-privacy">By submitting, you agree that the selected Armour branch may contact you about this inquiry.</p>
                    <p class="inquiry-error" data-inquiry-error role="alert" hidden></p>
                    <div class="inquiry-step-actions"><button type="button" data-inquiry-back>Back</button><button class="inquiry-primary" type="submit">Send inquiry</button></div>
                </div>
            </form>

            <div class="inquiry-success" data-inquiry-success hidden>
                <span aria-hidden="true">✓</span>
                <h2>Inquiry received</h2>
                <p data-inquiry-success-message></p>
                <button class="inquiry-primary" type="button" data-inquiry-done>Done</button>
            </div>
        @else
            <div class="inquiry-success"><h2>Contact a branch</h2><p>Branch information is being updated. Please check back shortly.</p></div>
        @endif
    </div>
</aside>
