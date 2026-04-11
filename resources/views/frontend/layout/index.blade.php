<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Raleway -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
    </style>

    {{-- Flatpicker kalender --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>{{ $title ?? 'Limasan514' }}</title>
</head>

<body>
    <main>
        <header>
            @include('frontend.layout.navbar')
        </header>
        <section>
            @yield('content')
        </section>
        <footer class="text-center text-lg-start bg-body-tertiary text-muted">
            @include('frontend.layout.footer')
        </footer>
    </main>
    {{-- back to top --}}
    <script>
        const backToTop = document.getElementById("backToTop");
    
    window.addEventListener("scroll", function(){
    
    if(window.scrollY > 300){
    backToTop.style.display = "flex";
    }else{
    backToTop.style.display = "none";
    }
    
    });
    
    backToTop.addEventListener("click", function(){
    
    window.scrollTo({
    top:0,
    behavior:"smooth"
    });
    
    });
    
    </script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let fullDates = [];
    
    // ambil data dari backend
    fetch('/full-dates')
        .then(res => res.json())
        .then(data => {
    
            fullDates = data;
    
            // init checkin
            flatpickr("#checkin", {
                dateFormat: "Y-m-d",
                disable: fullDates,
                minDate: "today",
                onChange: function(selectedDates, dateStr) {
                    checkoutPicker.set('minDate', dateStr);
                }
            });
    
            // init checkout
            window.checkoutPicker = flatpickr("#checkout", {
                dateFormat: "Y-m-d",
                disable: fullDates,
                minDate: "today"
            });
    
        });
        if(fullDates.length > 0){
            document.getElementById("warning_kamar").innerHTML ="⚠️ Beberapa tanggal sudah penuh, silakan pilih tanggal lain";
        }
</script>

</html>