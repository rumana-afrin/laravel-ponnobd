<div>
     <!--breadcrumb section -->
    <section class="breadcrumb" style="background-size: cover; background-repeat: no-repeat; background-image: url({{ asset('frontend/assets/img/bg.jpg') }});padding:40px" data-ll-status="entered">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread d-flex justify-content-center">
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <style>
        .loop-content {
          
            margin: 0 0px 56px !important;
    
            }
    </style>
     <!--breadcrumb section -->

    <section class="blogmain">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10">
                    <div class="row">
                        <div class="col-md-12">
                            @forelse ($posts as $post)
                            <div class="postloop">
                                <div class="loopimg text-center">
                                    <img src="{{ uploadedFile($post->thumbnail) }}" class="img-thumbnail" alt="{{ $post->thumbnail_alt }}">
                                </div>
                                <div class="loop-content">
                                    <span class="tagcat">{{ $post->category?->name }}</span>
                                    <a href="{{ route('blog.details',$post->slug) }}"><h2 class="looptitile">{{ $post->title }}</h2></a>
                                    <span class="dateandtime"><i class="bi bi-clock-fill"></i> {{ $post->created_at->format('d M Y') }}</span>
                                    <p>
                                        {{ Str::words(strip_tags($post->description),70) }}
                                    </p>
                                    <a href="{{ route('blog.details',$post->slug) }}" class="readmore">Read More <i class="bi bi-arrow-right-short"></i></a>
                                </div>
                            </div>
                            @empty
                            <div class="text-center">No Post Found!</div>
                            @endforelse

                            <nav aria-label="Page navigation">
                                {{ $posts->links() }}
                            </nav>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>








