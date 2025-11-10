@if ($paginator->hasPages())
    <nav>
        <ul class="pagination d-flex justify-content-center align-items-center" style="gap: 1rem; list-style: none;">

            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true">
                    <span style="font-size: 1.8rem; color: #ccc;">&#10094;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="font-size: 1.8rem; color: #000; text-decoration:none;">&#10094;</a>
                </li>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <a href="{{ $url }}" style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    width: 70px;
                                    height: 70px;
                                    background-color: #d9d9d9;
                                    border-radius: 50%;
                                    font-size: 1.5rem;
                                    color: #000;
                                    text-decoration: none;
                                    position: relative;
                                ">
                                    {{ $page }}
                                    <span style="
                                        position: absolute;
                                        bottom: -10px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        width: 60%;
                                        height: 4px;
                                        background-color: #d9d9d9;
                                        border-radius: 2px;
                                    "></span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    width: 50px;
                                    height: 50px;
                                    background-color: #d9d9d9;
                                    border-radius: 50%;
                                    font-size: 1.2rem;
                                    color: #000;
                                    text-decoration: none;
                                ">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="font-size: 1.8rem; color: #000; text-decoration:none;">&#10095;</a>
                </li>
            @else
                <li class="disabled" aria-disabled="true">
                    <span style="font-size: 1.8rem; color: #ccc;">&#10095;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
