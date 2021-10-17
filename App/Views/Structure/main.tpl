<!DOCTYPE html>
<html>
        <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
                <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
                <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
                <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
                <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
                <link id="pagestyle" href="assets/css/drifty.css?v=1.0.3" rel="stylesheet" />
                        <title>{% if ($page->title): %}{{ $page->title }}{% else: %}Drifty Framework{% endif %}</title>
                        <meta name="description" content="{% if ($page->description): %}{{ $page->description }}{% else: %}The Lightweight PHP Framework{% endif %}">
                        <meta property="og:type" content="website">
                        <meta property="og:title" content="{% if ($page->title): %}{{ $page->title }}{% else: %}Drifty Framework{% endif %}">
                        <meta property="og:description" content="{% if ($page->description): %}{{ $page->description }}{% else: %}The Lightweight PHP Framework{% endif %}">
                        <meta property="twitter:title" content="{% if ($page->title): %}{{ $page->title }}{% else: %}Drifty Framework{% endif %}">
                        <meta property="twitter:description" content="{% if ($page->description): %}{{ $page->description }}{% else: %}The Lightweight PHP Framework{% endif %}">
        </head>
        <body class="{% if ($page->background): %}{{ $page->background }}{% else: %}bg-gray-100{% endif %}">
                <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
                        {% if ($page->topNav == true): %}
                                {% include 'Structure/navbar/top.tpl' %}
                        {% endif %}
                        <div class="container-fluid {% if ($page->topNav == true): %}py-4{% endif %}">
                                {% section content %}
                        </div>
                </main>

                <!--   Core JS Files   -->
                <script src="assets/js/core/popper.min.js"></script>
                <script src="assets/js/core/bootstrap.min.js"></script>
                <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
                <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
                <script src="assets/js/plugins/chartjs.min.js"></script>
                {% section scripts %}
                <script src="assets/js/drifty.min.js?v=1.0.3"></script>
        </body>
</html>