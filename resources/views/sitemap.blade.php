<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{{ route('home') }}</loc></url>
  <url><loc>{{ route('products.index') }}</loc></url>
  <url><loc>{{ route('packages.index') }}</loc></url>
@foreach ($products as $product)
  <url>
    <loc>{{ route('products.show', $product) }}</loc>
    <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
  </url>
@endforeach
@foreach ($packages as $package)
  <url>
    <loc>{{ route('packages.show', $package) }}</loc>
    <lastmod>{{ $package->updated_at->toAtomString() }}</lastmod>
  </url>
@endforeach
</urlset>
