<aside class="aside-container">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
        <nav class="sidebar" data-sidebar-anyclick-close="">
            <!-- START sidebar nav-->
            <ul class="sidebar-nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <div class="collapse" id="user-block">
                        <div class="item user-block">
                            <!-- User picture-->
{{--                            <div class="user-block-picture">--}}
{{--                                <div class="user-block-status">--}}
{{--                                    <img class="img-thumbnail rounded-circle" src="{{URL::to('/')}}/img/user/no-avatar.png" alt="Avatar" width="60" height="60">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <!-- Name and Job-->
                            <div class="user-block-info pt-0">
                                <span class="user-block-name">{{Auth::user()->getName()}}</span>
                                <span class="user-block-role">{{Auth::user()->getGroup()}}</span>
                            </div>
                        </div>
                    </div>
                </li><!-- END user info-->
                <!-- Iterates over all sidebar items-->

                @if (Auth::user()->isSAdmin())
                    <li class="@if (Request::is('*users') || Request::is('*users/*')) active @endif">
                        <a href="{{ route('admin.users') }}" title="Пользователи">
                            <em class="fas fa-user-friends"></em>
                            <span>Пользователи</span>
                        </a>
                    </li>
                    <li class="@if (Request::is('*duties') || Request::is('*duties/*')) active @endif">
                        <a href="{{ route('admin.duties') }}" title="Рабочие обязанности">
                            <em class="fas fa-list"></em>
                            <span>Рабочие обязанности</span>
                        </a>
                    </li>
                @endif
            </ul><!-- END sidebar nav-->
        </nav>
    </div><!-- END Sidebar (left)-->
</aside>
