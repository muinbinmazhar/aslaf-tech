@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">ADMIN OVERVIEW</p>
            <h1>Dashboard</h1>
            <p class="page-description">ASLAF TECH অ্যাডমিন প্যানেলে স্বাগতম।</p>
        </div>
        <span class="page-badge">Admin Panel</span>
    </div>

    <section class="welcome-card" aria-labelledby="welcome-title">
        <div class="welcome-copy">
            <span class="welcome-label">WELCOME</span>
            <h2 id="welcome-title">Hello, {{ auth()->user()->name }}!</h2>
            <p>এখান থেকেই আপনার ওয়েবসাইটের কাজ পরিচালনা করতে পারবেন। নতুন মডিউল যুক্ত হলে সেগুলো এই প্যানেলে পাওয়া যাবে।</p>
        </div>
        <div class="welcome-mark" aria-hidden="true">A</div>
    </section>

    <section class="overview" aria-labelledby="overview-title">
        <div class="section-heading">
            <div><p class="eyebrow">WORKSPACE</p><h2 id="overview-title">Upcoming Modules</h2></div>
            <p>এই অংশগুলো ভবিষ্যতে যুক্ত হবে</p>
        </div>
        <div class="module-grid">
            <article class="module-card"><span class="module-icon" aria-hidden="true">01</span><h3>Our Apps</h3><p>অ্যাপ সম্পর্কিত কনটেন্ট পরিচালনার জায়গা।</p><span class="module-status">শীঘ্রই আসছে</span></article>
            <article class="module-card"><span class="module-icon" aria-hidden="true">02</span><h3>Services</h3><p>সেবার তথ্য পরিচালনার জায়গা।</p><span class="module-status">শীঘ্রই আসছে</span></article>
            <article class="module-card"><span class="module-icon" aria-hidden="true">03</span><h3>Portfolio</h3><p>প্রজেক্ট ও কাজের বিবরণ রাখার জায়গা।</p><span class="module-status">শীঘ্রই আসছে</span></article>
            <article class="module-card"><span class="module-icon" aria-hidden="true">04</span><h3>Blog</h3><p>লেখা ও আপডেট পরিচালনার জায়গা।</p><span class="module-status">শীঘ্রই আসছে</span></article>
            <article class="module-card"><span class="module-icon" aria-hidden="true">05</span><h3>Contact</h3><p>যোগাযোগ সংক্রান্ত কাজের জায়গা।</p><span class="module-status">শীঘ্রই আসছে</span></article>
        </div>
    </section>
@endsection
