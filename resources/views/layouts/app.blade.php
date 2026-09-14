<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Paróquia Nossa Senhora da Glória')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
        }
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1 0 auto;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-weight: bold;
            margin-right: 1rem;
            padding-top: 0.35rem;
            padding-bottom: 0.35rem;
        }
        .navbar-brand-logo-wrap {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
            border: 2px solid #f0d080;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
            background-color: #f4efe4;
        }
        .navbar-brand-logo {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 132%;
            height: 132%;
            max-width: none;
            transform: translate(-50%, -50%);
            object-fit: contain;
        }
        .navbar-brand-text {
            line-height: 1.15;
            font-size: 0.95rem;
        }
        @media (max-width: 575.98px) {
            .navbar-brand-logo-wrap {
                width: 44px;
                height: 44px;
            }
            .navbar-brand-text {
                font-size: 0.85rem;
            }
        }
        .navbar {
            background-color: #1a3a5c !important;
        }
        .navbar .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }
        .navbar .navbar-toggler-icon {
            filter: invert(1);
        }
        .navbar a {
            color: #fff !important;
        }
        .navbar a:hover {
            color: #f0d080 !important;
        }
        .footer {
            background-color: #1a3a5c;
            color: #cfd9e6;
            padding: 22px 0 0;
            margin-top: 32px;
            flex-shrink: 0;
            font-size: .88rem;
        }
        .footer a {
            color: #cfd9e6;
            text-decoration: none;
        }
        .footer a:hover {
            color: #f0d080;
        }
        .footer h6 {
            color: #f0d080;
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .3px;
            margin-bottom: .6rem !important;
        }
        /* Lista de navegacao do rodape em duas colunas nas telas grandes */
        .footer-links {
            display: grid;
            grid-template-columns: 1fr;
            gap: .3rem .9rem;
        }
        @media (min-width: 768px) {
            .footer-links { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        .footer-contato { display: grid; gap: .3rem; }
        .footer-links li, .footer-contato li { line-height: 1.35; }
        .footer-logo {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #f0d080;
            background-color: #f4efe4;
            flex-shrink: 0;
        }
        .footer-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            margin-top: 16px;
            padding: 10px 0;
            font-size: .8rem;
        }
        /* Redes sociais no rodapé */
        .social-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.12);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            transition: background-color .2s, color .2s;
        }
        .social-btn:hover {
            background-color: #f0d080;
            color: #1a3a5c;
        }
        /* Contato por WhatsApp */
        .btn-whats { background-color:#25d366; color:#0a3d1f; border:none; font-weight:600; }
        .btn-whats:hover { background-color:#1eb85a; color:#0a3d1f; }
        .navbar .btn-whats, .navbar .btn-whats:hover { color:#0a3d1f !important; }
        /* Barra de título padrão (páginas públicas e telas do admin) */
        .admin-topbar {
            background-color: #1a3a5c;
            color: #fff;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 22px;
        }
        .admin-topbar h1, .admin-topbar h2 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }
        .admin-topbar .topbar-sub {
            color: #cfd9e6;
            font-size: .88rem;
            margin: 2px 0 0;
        }
        .topbar-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background-color: #f0d080;
            color: #1a3a5c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        /* Cartões de conteúdo do admin e das páginas novas */
        .panel-card {
            background: #fff;
            border: 1px solid #e6e9ef;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(26, 58, 92, .06);
            overflow: hidden;
        }
        .panel-head {
            padding: 13px 18px;
            font-weight: 600;
            color: #1a3a5c;
            border-bottom: 1px solid #eef1f6;
            background-color: #fbfcfe;
        }
        .panel-body { padding: 16px 18px; }
        .stat-card {
            background: #fff;
            border: 1px solid #e6e9ef;
            border-left: 5px solid #1a3a5c;
            border-radius: 14px;
            padding: 16px 18px;
            height: 100%;
            box-shadow: 0 2px 10px rgba(26, 58, 92, .06);
        }
        .stat-card .stat-num { font-size: 2rem; font-weight: 700; color: #1a3a5c; line-height: 1; }
        .stat-card .stat-label { color: #6c757d; font-size: .9rem; }
        .stat-card .stat-extra { font-size: .82rem; color: #1a3a5c; }
        /* Faixa "Próxima missa" */
        .proxima-missa {
            background-color: #1a3a5c;
            color: #fff;
            border-radius: 14px;
            padding: 18px 22px;
        }
        .proxima-missa .rotulo {
            color: #f0d080;
            font-size: .78rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 700;
        }
        .proxima-missa .valor { font-size: 1.7rem; font-weight: 700; margin: 0; }
        /* Agenda semanal da home */
        .agenda-semana {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: .5rem;
        }
        .agenda-dia {
            background: #fff;
            border: 1px solid #e6e9ef;
            border-radius: 10px;
            text-align: center;
            padding: 10px 4px;
            height: 100%;
        }
        .agenda-dia .agenda-nome {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .5px;
            color: #6c757d;
            text-transform: uppercase;
        }
        .agenda-dia .agenda-hora { font-weight: 600; color: #1a3a5c; }
        .agenda-dia.hoje { background-color: #1a3a5c; border-color: #1a3a5c; }
        .agenda-dia.hoje .agenda-nome, .agenda-dia.hoje .agenda-hora { color: #fff; }
        .agenda-dia.domingo { background-color: #f0d080; border-color: #e6c267; }
        .agenda-dia.domingo .agenda-nome, .agenda-dia.domingo .agenda-hora { color: #1a3a5c; }
        /* Bloco "Onde estamos" */
        .mapa-embed { width: 100%; height: 100%; min-height: 300px; border: 0; display: block; }
        @media (max-width: 991.98px) {
            .mapa-embed { height: 280px; min-height: 0; }
        }
        /* Passo a passo numerado da página de sacramentos */
        .passo-num {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #1a3a5c;
            color: #fff;
            font-weight: 700;
            font-size: .9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        /* Cartões com foto (grupos e eventos) */
        .card-foto {
            width: 100%;
            height: 190px;
            object-fit: cover;
        }
        .card-missa {
            border-left: 4px solid #1a3a5c;
        }
        .badge-dia {
            background-color: #1a3a5c;
        }
        /* Hero da home — carrossel de imagens */
        .hero-igreja {
            position: relative;
            width: 100%;
            height: clamp(330px, 44vw, 500px);
            min-height: 330px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .hero-igreja-slides {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center center;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .hero-slide.active {
            opacity: 1;
        }
        .hero-slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(26,58,92,0.55), rgba(26,58,92,0.65));
        }
        .hero-igreja .container {
            position: relative;
            z-index: 1;
        }
        .hero-igreja h1 {
            font-weight: 700;
            font-size: 2.6rem;
            text-shadow: 0 2px 12px rgba(0,0,0,0.45);
        }
        .hero-igreja p.lead {
            font-size: 1.2rem;
            text-shadow: 0 1px 8px rgba(0,0,0,0.45);
        }
        .hero-igreja .btn-hero {
            background-color: #f0d080;
            color: #1a3a5c;
            font-weight: 600;
            border: none;
        }
        .hero-igreja .btn-hero:hover {
            background-color: #e6c267;
            color: #1a3a5c;
        }
        /* Carrossel da página Sobre — imagens inteiras (sem corte) */
        #carrosselSobre {
            background-color: #1a3a5c;
        }
        .carousel-img {
            width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            background-color: #1a3a5c;
        }
        .carousel-caption {
            background: rgba(26, 58, 92, 0.75);
            border-radius: 8px;
            padding: 10px 18px;
            bottom: 1.5rem;
        }
        .carousel-caption h5 {
            font-weight: 700;
            margin-bottom: 4px;
        }
        /* ===== Cabecalho de secao: titulo + link "ver todos" ===== */
        .section-head {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            justify-content: space-between;
            gap: .15rem 1rem;
            padding-bottom: .5rem;
            border-bottom: 1px solid #e3e8ef;
        }
        .section-head h5 {
            min-width: 0;
            font-size: 1.12rem;
        }
        .section-head > a {
            color: #1a3a5c;
            font-weight: 600;
            white-space: nowrap;
        }
        .section-head > a:hover { color: #0f2a45; }

        /* ===== Faixa flexivel: texto a esquerda, acao a direita ===== */
        .faixa-flex {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .75rem 1rem;
        }

        /* ===== Botoes do hero ===== */
        .hero-acoes {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .6rem;
        }

        /* ===== Navbar: 9 itens + botao so cabem a partir de 1200px ===== */
        @media (min-width: 1200px) {
            .navbar-nav .nav-link {
                padding-left: .45rem;
                padding-right: .45rem;
                font-size: .88rem;
                white-space: nowrap;
            }
        }
        /* Faixa 1200-1399px: e o ponto mais apertado do menu, comprime mais */
        @media (min-width: 1200px) and (max-width: 1399.98px) {
            .navbar-nav .nav-link {
                padding-left: .3rem;
                padding-right: .3rem;
                font-size: .8rem;
            }
            .navbar-brand { margin-right: .5rem !important; gap: .45rem; }
            .navbar-brand-text { font-size: .8rem; }
            .navbar-brand-logo-wrap { width: 42px; height: 42px; }
            .navbar .btn-whats { font-size: .78rem; padding: .25rem .5rem; }
        }
        @media (min-width: 1400px) {
            .navbar-nav .nav-link {
                padding-left: .6rem;
                padding-right: .6rem;
                font-size: .95rem;
            }
        }
        /* Menu recolhido (ate 1199px): itens com area de toque e rolagem */
        @media (max-width: 1199.98px) {
            .navbar-collapse {
                max-height: 78vh;
                overflow-y: auto;
                padding-bottom: .5rem;
            }
            .navbar-nav .nav-link {
                padding-top: .55rem;
                padding-bottom: .55rem;
            }
        }

        /* ===== Ajustes de responsividade para tablet ===== */
        @media (max-width: 767.98px) {
            .section-head h5 { font-size: 1.02rem; }
            .admin-topbar { padding: 14px 16px; }
            .admin-topbar h1, .admin-topbar h2 { font-size: 1.1rem; }
        }

        /* ===== Ajustes de responsividade para celular ===== */
        @media (max-width: 575.98px) {
            /* Agenda semanal: mantem os 7 dias na mesma linha, so que compactos */
            .agenda-semana { gap: .25rem; }
            .agenda-dia {
                padding: 7px 1px;
                border-radius: 8px;
            }
            .agenda-dia .agenda-nome { font-size: .58rem; letter-spacing: 0; }
            .agenda-dia .agenda-hora { font-size: .68rem; }

            /* Hero: titulo e botoes proporcionais a tela pequena */
            .hero-igreja { height: clamp(300px, 70vw, 380px); min-height: 300px; margin-bottom: 22px; }
            .hero-igreja h1 { font-size: 1.6rem; }
            .hero-igreja p.lead { font-size: .98rem; }
            .hero-acoes { flex-direction: column; align-items: stretch; }
            .hero-acoes .btn { width: 100%; }

            /* Faixa "Proxima missa": valor nao estoura a largura */
            .proxima-missa { padding: 14px 16px; }
            .proxima-missa .valor { font-size: 1.22rem; }
            .faixa-flex > .btn { width: 100%; }

            /* Rodape: espacamentos menores no celular */
            .footer { padding-top: 18px; margin-top: 24px; font-size: .85rem; }
            .footer-bottom { text-align: center; justify-content: center !important; }
            .footer-contato .ms-3 { margin-left: 0 !important; }
        }

        /* Nenhum bloco deve empurrar a pagina para os lados */
        img, iframe, table { max-width: 100%; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-xl navbar-dark">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand me-3" href="{{ route('home') }}" title="Paróquia Nossa Senhora da Glória">
                <span class="navbar-brand-logo-wrap">
                    <img src="{{ asset('images/logoigreja.png') }}" alt="" class="navbar-brand-logo" aria-hidden="true">
                </span>
                <span class="navbar-brand-text">Paróquia N. S. da Glória</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('missas.index') }}">
                            <i class="bi bi-clock"></i> Missas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('eventos.index') }}">
                            <i class="bi bi-calendar-event"></i> Eventos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('grupos.index') }}">
                            <i class="bi bi-people"></i> Grupos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('catequese') }}">
                            <i class="bi bi-book"></i> Catequese
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sacramentos') }}">
                            <i class="bi bi-heart"></i> Sacramentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('avisos.index') }}">
                            <i class="bi bi-megaphone"></i> Avisos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sobre') }}">
                            <i class="bi bi-info-circle"></i> Sobre
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contato') }}">
                            <i class="bi bi-envelope"></i> Contato
                        </a>
                    </li>

                    {{-- Contato por WhatsApp com a secretaria (aparece em todas as páginas) --}}
                    <li class="nav-item d-flex align-items-center ms-xl-2 me-xl-1 my-2 my-xl-0">
                        <x-whatsapp-btn
                            mensagem="Olá, vim pelo site da paróquia e gostaria de falar com a secretaria."
                            rotulo="Secretaria" />
                    </li>

                    {{-- Login/Cadastro removidos da barra pública: site informativo.
                         O administrador acessa o painel entrando por /login diretamente. --}}
                    @auth
                        @if(Auth::user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.index') }}">
                                <i class="bi bi-gear"></i> Admin
                            </a>
                        </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <main>
        @yield('hero')

        <div class="container mt-4">
            @if(session('sucesso'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('sucesso') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('erro'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('erro') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer institucional -->
    <footer class="footer">
        <div class="container">
            <div class="row g-3 g-lg-4">

                {{-- Marca --}}
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="footer-logo">
                            <img src="{{ asset('images/logoigreja.png') }}" alt="Logo da Paróquia Nossa Senhora da Glória">
                        </span>
                        <div>
                            <strong class="text-white d-block">Paróquia Nossa Senhora da Glória</strong>
                            <small>Igreja Católica Ucraniana · Rito Bizantino</small>
                        </div>
                    </div>
                    <p class="small mb-2">
                        Fundada em 1952 por imigrantes ucranianos, a paróquia mantém viva a tradição
                        religiosa e cultural da comunidade de Pitanga e região.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="https://www.instagram.com/pnsg_1/" target="_blank" rel="noopener"
                           class="social-btn" aria-label="Instagram da paróquia">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.facebook.com/pnsgpitanga/?locale=pt_BR" target="_blank" rel="noopener"
                           class="social-btn" aria-label="Facebook da paróquia">
                            <i class="bi bi-facebook"></i>
                        </a>
                    </div>
                </div>

                {{-- Navegação --}}
                <div class="col-6 col-lg-3">
                    <h6 class="mb-3">Navegação</h6>
                    <ul class="list-unstyled small mb-0 footer-links">
                        <li><a href="{{ route('home') }}">Início</a></li>
                        <li><a href="{{ route('missas.index') }}">Horários de Missas</a></li>
                        <li><a href="{{ route('eventos.index') }}">Eventos</a></li>
                        <li><a href="{{ route('grupos.index') }}">Grupos</a></li>
                        <li><a href="{{ route('catequese') }}">Catequese</a></li>
                        <li><a href="{{ route('sacramentos') }}">Sacramentos</a></li>
                        <li><a href="{{ route('avisos.index') }}">Avisos</a></li>
                        <li><a href="{{ route('sobre') }}">Sobre a Paróquia</a></li>
                    </ul>
                </div>

                {{-- Contato --}}
                <div class="col-6 col-lg-4">
                    <h6 class="mb-3">Contato</h6>
                    <ul class="list-unstyled small mb-0 footer-contato">
                        <li>
                            <i class="bi bi-geo-alt"></i> Rua Conselheiro Zacarias, 295<br>
                            <span class="ms-3">85200-053, Pitanga, Paraná</span>
                        </li>
                        <li><i class="bi bi-telephone"></i> {{ config('paroquia.telefone') }}</li>
                        <li><a href="{{ route('contato') }}"><i class="bi bi-envelope"></i> Fale conosco</a></li>
                        <li>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=Par%C3%B3quia+Nossa+Senhora+da+Gl%C3%B3ria%2C+Pitanga+-+PR"
                               target="_blank" rel="noopener">
                                <i class="bi bi-map"></i> Como chegar
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
                <span>&copy; {{ date('Y') }} Paróquia Nossa Senhora da Glória. Todos os direitos reservados.</span>
                <span>Sistema desenvolvido por alunos do curso de Engenharia de Software</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
