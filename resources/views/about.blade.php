@extends('layouts.app')

@section('title', 'About Armour | Built for Better Drives')
@section('meta_description', 'Meet Armour Philippines, your partner for dependable in-car technology, accessories, and professional installation.')

@section('content')
@php
  $aboutBodyWithLegacyBoldTags = str_replace(
      ['<bold>', '</bold>'],
      ['<strong>', '</strong>'],
      $about['body'],
  );
  $safeAboutBody = str($aboutBodyWithLegacyBoldTags)->sanitizeHtml();
@endphp
<section class="simple-about">
  <div class="container simple-about-inner reveal">
    <p class="eyebrow"><span></span> About Armour</p>
    <h1>{{ $about['heading'] }}</h1>
    <div class="simple-about-copy">{!! $safeAboutBody !!}</div>
    <div class="hero-actions">
      <a class="button" href="{{ route('products.index') }}">Explore products</a>
      <a class="button button-ghost" href="{{ route('dealers') }}">Authorized dealers</a>
    </div>
  </div>
</section>
@endsection
