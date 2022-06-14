<div class="container-fluid">
    <div class="row long-name text-center">
        <div class="col-12">
            {{ config('app.long_name') }}
        </div>
    </div>

</div>
<nav class="navbar navbar-expand-md navbar-light shadow bg-white">
    <div class="container-fluid">

        <a class="py-1 navbar-brand" href="{{ url('/') }}" >
            <img src="/img/logo-alerj-docigp.png" class="img-fluid logo-alerj " alt="">
        </a>

        <div class="d-flex ml-auto">
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#globalNavbar" aria-controls="globalNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse" id="globalNavbar" style="">


            <ul class="navbar-nav mr-auto">

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @else
                    <li class="nav-item">
{{--                        <a class="nav-link" href="{{ route('admin.index') }}">Painel de Controle</a>--}}
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Tabelas <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('entries.index') }}">
                                Prestação de Contas
                            </a>

                            @can('parties:show')
                                <a class="dropdown-item" href="{{ route('parties.index') }}">
                                    Partidos
                                </a>
                            @endCan

                            @can('legislatures:show')
                                <a class="dropdown-item" href="{{ route('legislatures.index') }}">
                                    Legislaturas
                                </a>
                            @endCan

                            @can('congressmen:show')
                                <a class="dropdown-item" href="{{ route('congressmen.index') }}">
                                    Deputados
                                </a>
                            @endCan

                            @can('users:show')
                                <a class="dropdown-item" href="{{ route('users.index') }}">
                                    Usuários
                                </a>
                            @endCan

                            @can('providers:show')
                                <a class="dropdown-item" href="{{ route('providers.index') }}">
                                    Fornecedores / Favorecidos
                                </a>
                            @endCan

                            @can('cost-centers:show')
                                <a class="dropdown-item" href="{{ route('cost-centers.index') }}">
                                    Centro de Custo
                                </a>
                            @endCan

                            @can('entry-types:show')
                                <a class="dropdown-item" href="{{ route('entry-types.index') }}">
                                    Tipos de Lançamentos
                                </a>
                            @endCan

                            @can('annual-reports:view')
                                <a class="dropdown-item" href="{{ route('annual-reports.index') }}">
                                    Relatórios Anuais
                                </a>
                            @endCan

                            @can('audits:all-show')
                                <a class="dropdown-item" href="{{ route('audits.index') }}">
                                    Logs de atividades
                                </a>
                            @endCan

                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                                                                                                                                         document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest


            </ul>
        </div>
    </div>
</nav>
