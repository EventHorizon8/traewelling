@extends('layouts.app')

@section('title')
    @if($page == "home")
        {{__('menu.blog')}}
    @elseif($page == "single")
        {{ $blogposts[0]->title }}
    @elseif($page == "category")
        {{__('menu.blog')}}: {{ $category }}
    @endif
@endsection

@section('content')
    <div class="container blog">
        <div class="row justify-content-center">
            <div class="col-md-9">
                @if($page == "single")
                    <p><a href="{{route('blog.all')}}">{!! __('pagination.back') !!}</a></p>

                @elseif($page == "category")

                    <p><a href="{{route('blog.all')}}">{!! __('pagination.back') !!}</a></p>
                    <h1><i class="fa fa-tag pe-2"></i>{{$category}}</h1>
                @endif

                @foreach($blogposts as $blogpost)
                    <div class="mt-5">
                        <div>
                            <a href="{{ route('blog.show', ['slug' => $blogpost->slug]) }}">
                                <h3 class="font-weight-bold">{{$blogpost->title}}</h3>
                            </a>
                        </div>
                        <div>
                            @if($page == "home")
                                <div class="row">
                                    <div class="col-md-10"{!! Markdown::parse($blogpost->preview) !!}</div>
                                <div class="col-md-2">
                                    <a class="mt-0 mb-5" href="{{ route('blog.show', ['slug' => $blogpost->slug]) }}">
                                        {{ __('menu.readmore') }} &raquo;
                                    </a>
                                </div>
                        </div>
                        @else
                            {!! Markdown::parse($blogpost->body) !!}
                        @endif

                    </div>
                    <div class="text-muted">
                        <p class="float-end">
                            <samp class="pe-2">{{ $blogpost->published_at->format('d.m.Y') }}</samp>
                        </p>
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                @if(!empty($blogpost->twitter_handle))
                                    <a href="https://twitter.com/{{ $blogpost->twitter_handle }}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif

                                {{ $blogpost->author_name }}
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ route('blog.category', ['category' => $blogpost->category]) }}">
                                    <i class="fa fa-tag"></i>
                                    {{$blogpost->category}}
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($blogpost->title . ' ' .
                                route('blog.show', ['slug' => $blogpost->slug]) . ' via @traewelling' ) }}">Tweet</a>
                            </li>
                        </ul>
                    </div>
            </div>
            <hr class="w-100"/>
            @endforeach
        </div>
    </div>
    <div class="row justify-content-center">
        {{ $blogposts->links() }}
    </div>
    </div>
@endsection
