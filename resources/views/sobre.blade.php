@extends('layouts.app')

@section('title', 'Sobre a Paróquia')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <h1 class="mb-1"><i class="bi bi-info-circle" aria-hidden="true"></i> Sobre a Paróquia</h1>
        <p class="text-muted mb-4">Paróquia Nossa Senhora da Glória, Pitanga, PR</p>

        {{-- Carrossel de imagens da paróquia (Sprint 3) --}}
        <div id="carrosselSobre" class="carousel slide carousel-fade shadow-sm mb-4 rounded overflow-hidden"
             data-bs-ride="carousel" data-bs-interval="4500">
            <div class="carousel-indicators">
                @for($i = 0; $i < 7; $i++)
                    <button type="button" data-bs-target="#carrosselSobre" data-bs-slide-to="{{ $i }}"
                            class="{{ $i === 0 ? 'active' : '' }}"
                            @if($i === 0) aria-current="true" @endif
                            aria-label="Foto {{ $i + 1 }}"></button>
                @endfor
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/sobre1.jpeg') }}" class="d-block w-100 carousel-img"
                         alt="Paróquia Nossa Senhora da Glória">
                    <div class="carousel-caption d-none d-md-block">
                        <p class="fs-5 fw-bold mb-1">Nossa Igreja</p>
                        <p>Casa de fé e acolhimento desde 1952.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/sobre2.jpeg') }}" class="d-block w-100 carousel-img"
                         alt="Celebração na paróquia">
                    <div class="carousel-caption d-none d-md-block">
                        <p class="fs-5 fw-bold mb-1">Celebrações</p>
                        <p>Rito bizantino ucraniano celebrado todos os domingos.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/sobre3.jpeg') }}" class="d-block w-100 carousel-img"
                         alt="Comunidade reunida">
                    <div class="carousel-caption d-none d-md-block">
                        <p class="fs-5 fw-bold mb-1">Comunidade</p>
                        <p>Fiéis e famílias unidos em oração e tradição.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/sobre4.jpeg') }}" class="d-block w-100 carousel-img"
                         alt="Atividades culturais">
                    <div class="carousel-caption d-none d-md-block">
                        <p class="fs-5 fw-bold mb-1">Cultura Ucraniana</p>
                        <p>Dança, música e tradições preservadas vivas.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/sobre6.jpeg') }}" class="d-block w-100 carousel-img"
                         alt="Eventos da paróquia">
                    <div class="carousel-caption d-none d-md-block">
                        <p class="fs-5 fw-bold mb-1">Eventos e Festas</p>
                        <p>Momentos que fortalecem nossa comunidade.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/sobre7.jpg') }}" class="d-block w-100 carousel-img"
                         alt="Tradição e fé">
                    <div class="carousel-caption d-none d-md-block">
                        <p class="fs-5 fw-bold mb-1">Tradição e Fé</p>
                        <p>A herança dos imigrantes ucranianos mantida viva.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/sobre8.jpg') }}" class="d-block w-100 carousel-img"
                         alt="Vida paroquial">
                    <div class="carousel-caption d-none d-md-block">
                        <p class="fs-5 fw-bold mb-1">Vida Paroquial</p>
                        <p>Catequese, devoções e ação social todos os dias.</p>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carrosselSobre" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carrosselSobre" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>

        {{-- Identificação e endereço --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-geo-alt"></i> Paróquia Nossa Senhora da Glória, Pitanga/PR</h2>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Endereço:</strong> {{ config('paroquia.endereco.logradouro') }}, {{ config('paroquia.endereco.numero') }}, {{ config('paroquia.endereco.bairro') }}, {{ config('paroquia.endereco.cep') }}, {{ config('paroquia.endereco.cidade') }}, {{ config('paroquia.endereco.estado') }}</p>
                <p class="mb-1"><strong>Telefone:</strong> {{ config('paroquia.telefone') }} · <strong>Celular/WhatsApp:</strong> {{ config('paroquia.celular') }}</p>
                <p class="mb-1"><strong>E-mail:</strong> <a href="mailto:{{ config('paroquia.email') }}">{{ config('paroquia.email') }}</a></p>
                <p class="mb-1"><strong>CNPJ:</strong> {{ config('paroquia.cnpj') }}</p>
                <p class="mb-0"><strong>Igreja Católica Ucraniana</strong>, Rito Bizantino</p>
            </div>
        </div>

        {{-- Padroeira --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-star"></i> Nossa Padroeira</h2>
            </div>
            <div class="card-body">
                <p class="mb-0">A festa de <strong>Nossa Senhora da Glória</strong> é celebrada no dia
                   <strong>15 de agosto</strong>. Em respeito à padroeira, a data é
                   <strong>feriado municipal em Pitanga</strong>.</p>
            </div>
        </div>

        {{-- História --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-clock-history"></i> A comunidade ucraniana em Pitanga</h2>
            </div>
            <div class="card-body">
                <p>Pitanga fica no centro do Paraná e, no início, chamava-se <strong>Serra da Pitanga</strong>.
                   A colonização começou em <strong>1924</strong>. As primeiras famílias ucranianas vieram de
                   Prudentópolis: <strong>Frederico Repula, Miguel Hulek e Fernando Maiko</strong>.</p>

                <p>O primeiro missionário foi o <strong>Pe. Marciano Skhirpan, OSBM</strong>, que vinha de
                   Prudentópolis montado a cavalo e se hospedava na casa da família de Frederico e Tecla Repula.
                   No começo, celebrava na igreja católica romana; depois foi construída uma capela perto da casa
                   de Frederico, abençoada pelo <strong>Monsenhor João Buchko</strong>.</p>

                <p>Como a capela não comportava tanta gente, o Pe. Marciano e o Pe. Paulo, da Igreja Latina,
                   foram até Guarapuava (Pitanga pertencia a Guarapuava naquela época) falar com o prefeito,
                   que cedeu duas áreas maiores da cidade. A comunidade Santana escolheu o seu local, e ficou
                   para a comunidade ucraniana o ponto mais alto: o nosso terreno de hoje.</p>

                <p>Anos depois, com o falecimento do Pe. Marciano, chegou o <strong>Pe. Orestes Carpilhuk</strong>,
                   também vindo da Ucrânia. Padre jovem e disposto, gostava de andar a cavalo; os escritos contam
                   que o seu cavalo era preto, com uma estrela branca na testa. Em <strong>1939</strong> o Pe. Orestes
                   organizou a comissão da igreja e deu início à <strong>primeira igreja de madeira</strong>. Em
                   <strong>1943</strong> a comunidade passou a ser administrada pelo <strong>Pe. José Martenetz</strong>,
                   depois <strong>Bispo Dom José</strong>.</p>

                <p>Ao longo de todos esses anos, a comunidade sempre teve acompanhamento espiritual por meio das
                   <strong>Santas Missões</strong>, realizadas em 1950, 1960, 1972, 1992, 2005, 2015 e 2024.</p>

                <p>As <strong>Irmãs Servas de Maria Imaculada (SMI)</strong> exercem grande trabalho pastoral na
                   paróquia e nas demais capelas, acompanhando crianças, adolescentes, jovens e o Apostolado da
                   Oração. Desde o início até hoje, cada sacerdote que atendeu a comunidade lançou a semente da
                   Palavra de Deus no coração de muitas pessoas, e essa semente germinou, produziu e continua
                   produzindo bons frutos. Daquela época até os dias de hoje a comunidade se estruturou pastoral
                   e economicamente, e a <strong>nossa igreja é considerada um dos pontos turísticos de Pitanga</strong>.</p>

                <p class="text-muted small mb-0">
                    <i class="bi bi-journal-text"></i>
                    Texto baseado no registro histórico escrito pela própria comunidade ucraniana de Pitanga.
                </p>
            </div>
        </div>

        {{-- Linha do tempo --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-signpost-split"></i> Linha do tempo</h2>
            </div>
            <div class="card-body">
                <ul class="mb-3">
                    <li><strong>1924</strong>: início da colonização; chegam as primeiras famílias ucranianas vindas de Prudentópolis</li>
                    <li><strong>1939</strong>: o Pe. Orestes Carpilhuk organiza a comissão e inicia a primeira igreja, de madeira</li>
                    <li><strong>1943</strong>: a comunidade passa a ser administrada pelo Pe. José Martenetz, depois Bispo Dom José</li>
                    <li><strong>1952</strong>: criação da paróquia pelo Decreto nº 01/52, expedido por Dom Jaime de Barros Cardeal Câmara</li>
                    <li><strong>1974</strong>: o Bispo Dom Efraim faz a bênção da pedra fundamental da igreja atual</li>
                    <li><strong>16 de agosto de 1981</strong>: inauguração da igreja atual pelo <strong>Bispo Dom Efraim Basílio Krevey, SBM</strong>, com a presença de 22 sacerdotes e de fiéis de Pitanga e de outras localidades</li>
                </ul>
                <p class="mb-1"><strong>As três igrejas da comunidade</strong></p>
                <ul class="mb-0">
                    <li><strong>1ª igreja:</strong> de madeira, iniciada em 1939</li>
                    <li><strong>2ª igreja:</strong> construída entre 1953 e 1955</li>
                    <li><strong>Igreja atual:</strong> em alvenaria, construída entre 1974 e 1981</li>
                </ul>
            </div>
        </div>

        {{-- Missão --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-heart"></i> Nossa Missão</h2>
            </div>
            <div class="card-body">
                <p class="mb-0">Evangelizar e acolher todos os fiéis, promovendo a fé católica de rito bizantino ucraniano,
                   fortalecendo a identidade cultural e a integração da comunidade por meio das atividades religiosas e sociais.</p>
            </div>
        </div>

        {{-- Sacerdotes --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-person-badge"></i> Sacerdotes da Paróquia</h2>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Pároco:</strong> Pe. Mateus Krefer, OSBM</p>
                <p class="mb-1"><strong>Vigários Paroquiais:</strong></p>
                <ul class="mb-0">
                    <li>Pe. Carlos Melnicki, OSBM</li>
                    <li>Pe. José Novossad, OSBM</li>
                    <li>Pe. Paulo Markiv, OSBM</li>
                    <li>Pe. Sérgio Taras Iwantschuk, OSBM</li>
                </ul>
            </div>
        </div>

        {{-- Comunidades religiosas --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-building"></i> Comunidades de Vida Consagrada</h2>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Residência Paroquial</li>
                    <li>Convento das <strong>Irmãs Servas de Maria Imaculada</strong> em Pitanga e em Palmital, onde dirigem duas escolas de 1º Grau e Pré-Primário em Pitanga, PR</li>
                    <li>Residência das <strong>Catequistas do Sagrado Coração de Jesus</strong>, estabelecida em Ivaiporã, PR, onde dirigem uma escola de 1º Grau de sua propriedade</li>
                </ul>
            </div>
        </div>

        {{-- Atividades pastorais --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-bookmark-heart"></i> Atividades Pastorais</h2>
            </div>
            <div class="card-body">
                <h3 class="card-titulo">Catequese</h3>
                <p>Realiza-se <strong>somente aos sábados, das 8h30 às 11h30</strong>, ministrada pelas
                   Irmãs Servas de Maria Imaculada, Catequistas do Sagrado Coração e por catequistas leigas.</p>

                <h3 class="card-titulo mt-3">Cultos e Devoções Tradicionais</h3>
                <ul>
                    <li><strong>Quaresma:</strong> Via-Sacra, Missa dos Dons Pré-Santificados, celebrações da Semana Santa e tríduos de renovação espiritual</li>
                    <li><strong>Maio:</strong> tradicional novena (<em>Maivka</em>)</li>
                    <li><strong>Junho:</strong> Novena ao Sagrado Coração de Jesus</li>
                    <li><strong>Outubro:</strong> Rosário comunitário</li>
                    <li>Periodicamente, missões realizadas pelos Padres Basilianos</li>
                </ul>

                <h3 class="card-titulo mt-3">Ação Social</h3>
                <p class="mb-0">Periodicamente realizam-se festas e promoções com a finalidade de manter a Paróquia e suas obras.</p>
            </div>
        </div>

        {{-- Associações --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-people"></i> Associações Religioso-Culturais</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li><i class="bi bi-check-circle text-success me-2"></i> Apostolado da Oração</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Grupo de Jovens</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Congregação Mariana</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Cruzada Eucarística</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Cursilhos de Cristandade</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li><i class="bi bi-check-circle text-success me-2"></i> Coral Misto</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Grupo Folclórico</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Comissão Administrativa Paroquial (CAP)</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Comissão Paroquial de Catequese</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Comunidades --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h2 class="h6 mb-0"><i class="bi bi-map"></i> Comunidades Atendidas</h2>
            </div>
            <div class="card-body">
                <p class="text-muted">A Paróquia atende mais de 20 comunidades em diversas localidades.
                   Clique para expandir os detalhes.</p>

                <div class="accordion" id="accComunidades">
                    @php
                        $comunidades = [
                            ['Água Fria (Pitanga)', 'Nossa Senhora da Luz', '08', '15 km', 'Igreja construída em 1983 e a atual de 2002, ambas em madeira.'],
                            ['Ariranha (sede)', 'São Miguel', '17', '60 km', 'Única igreja construída, em madeira, data do ano 1965.'],
                            ['Arroio Grande (Pitanga)', 'Nossa Senhora Aparecida', '27', '18 km', 'Única igreja construída, em madeira, data do ano 1976.'],
                            ['Barra do Divino Espírito Santo (Pitanga)', 'Divino Espírito Santo', '35', '17 km', '1ª igreja construída em 1991 e a atual em 2002, ambas em alvenaria.'],
                            ['Barra Grande (Palmital)', 'Anjo da Guarda', '10', '100 km', 'Única igreja construída, em madeira, data do ano 1981.'],
                            ['Barreirinho de Baixo (Nova Tebas)', 'Nossa Senhora Imaculada Conceição', '76', '46 km', '1ª igreja em madeira de 1955; atual em alvenaria entre 1984-87.'],
                            ['Barreirinho do Trevo (Nova Tebas)', 'Santo Anjo da Guarda', '28', '43 km', 'Única igreja construída, em alvenaria, data do ano 1998.'],
                            ['Boa Ventura de São Roque (sede)', 'Arcanjo São Miguel', '70', '50 km', '1ª igreja de 1942, 2ª de 1948 (ambas em madeira), atual em alvenaria de 1992.'],
                            ['Borboleta Abaixo (Pitanga)', 'Nossa Senhora do Patrocínio', '70', '23 km', 'Única igreja construída, em alvenaria, data do ano 1998.'],
                            ['Ivaiporã (sede)', 'São José', '70', '80 km', 'Única igreja construída, em alvenaria, data do ano 1976.'],
                            ['Manoel Ribas (sede)', 'Cristo Rei', '72', '32 km', 'Única igreja construída, em alvenaria, data do ano 1990.'],
                            ['Palmital (sede)', 'Bom Jesus / Transfiguração', '112', '75 km', '1ª igreja de 1951, 2ª de 1956 (ambas em madeira), atual em alvenaria de 1985.'],
                            ['Pinhal (Cândido de Abreu)', 'Natividade de Nossa Senhora', '67', '78 km', '1ª igreja de 1935, 2ª de 1960, atual em alvenaria de 1990.'],
                            ['Pitanga Abaixo (Pitanga)', 'São Pedro e São Paulo', '39', '19 km', 'Única igreja construída, em madeira, entre 1976-83.'],
                            ['Rio 15 de Baixo (Pitanga)', 'Santo Antonio de Pádua', '32', '23 km', 'Única igreja construída, em alvenaria, data do ano 1991.'],
                            ['Rio 15 de Cima (Pitanga)', 'São Pedro e São Paulo', '21', '30 km', 'Única igreja construída, em madeira, data do ano 1954.'],
                            ['Santa Maria d\'Oeste (sede)', 'Natividade de Nossa Senhora', '84', '40 km', '1ª igreja em madeira de 1953; atual em alvenaria de 1988.'],
                            ['São José (Santa Maria d\'Oeste)', 'São João Batista', '20', '60 km', '1ª igreja em madeira de 1953; atual em alvenaria de 1991.'],
                            ['Vila Burey (Palmital)', 'Nossa Senhora Aparecida', '25', '70 km', '1ª igreja em madeira de 1967; atual em alvenaria de 1994.'],
                            ['Vorá de Cima (Pitanga)', 'São Nicolau', '30', '40 km', 'Única igreja construída, em madeira, data do ano 1968.'],
                        ];
                    @endphp

                    @foreach($comunidades as $idx => $c)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#com{{ $idx }}">
                                    <strong>{{ $c[0] }}</strong>
                                    <span class="badge bg-secondary ms-2">{{ $c[2] }} famílias</span>
                                    <span class="badge text-bg-light ms-1">{{ $c[3] }}</span>
                                </button>
                            </h2>
                            <div id="com{{ $idx }}" class="accordion-collapse collapse" data-bs-parent="#accComunidades">
                                <div class="accordion-body">
                                    <p class="mb-1"><strong>Padroeiro:</strong> {{ $c[1] }}</p>
                                    <p class="mb-1"><strong>Distância da sede:</strong> {{ $c[3] }}</p>
                                    <p class="mb-0"><small class="text-muted">{{ $c[4] }}</small></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="alert alert-light border mt-3 mb-0">
                    <small>
                        <i class="bi bi-info-circle"></i>
                        As comunidades de <strong>Bom Retiro</strong> (Santa Terezinha), <strong>Barra Bonita</strong>
                        (Santa Terezinha), <strong>Rio Liso</strong> (São José), <strong>Água Fria</strong>
                        (Nossa Senhora da Luz), <strong>Linha Cantú</strong> (Santa Rita de Cássia) e
                        <strong>Rio Quieto</strong> (São José), totalizando <strong>74 famílias ucranianas</strong>,
                        também são atendidas no seu rito pelos padres da Paróquia ucraniana de Pitanga.
                    </small>
                </div>
            </div>
        </div>

        {{-- Fonte e botões --}}
        <div class="text-center mt-4 mb-4">
            <a href="{{ route('contato') }}" class="btn text-white me-2" style="background-color:#1a3a5c;">
                <i class="bi bi-envelope"></i> Entre em Contato
            </a>
            <a href="{{ route('grupos.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-people"></i> Ver Grupos
            </a>
            <a href="https://metropolia.org.br/eparquia/pitanga/" target="_blank" rel="noopener"
               class="btn btn-outline-secondary">
                <i class="bi bi-box-arrow-up-right"></i> Site da Eparquia
            </a>
        </div>
    </div>
</div>
@endsection
