<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family==Roboto:wght@100;300&display=swap">


    <!-- Styles -->
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="antialiased">
<!--Navbar -->
<nav class="relative container mx-auto p-6">
    <div class="flex items-center justify-between">
        <div class="pt-2">
            <a href="index.html">
                <img src="{{asset('assets/image/logo.jpeg')}}" class="block h-25" alt="Here">

            </a>
        </div>
        <!-- <div class="hidden md:flex space-x-6">
            <a href="#" class="hover:text-darkGrayishBlue">Pricing</a>
            <a href="#" class="hover:text-darkGrayishBlue">Product</a>
            <a href="#" class="hover:text-darkGrayishBlue">About Us</a>
            <a href="#" class="hover:text-darkGrayishBlue">Careers</a>
            <a href="#" class="hover:text-darkGrayishBlue">Community</a>
        </div> -->
        <div class="hidden md:flex space-x-6">
            <!---Button -->
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="md:block p-3 px-6 pt-2 pb-2 text-white bg-brightGreen rounded-full baseline hover:bg-veryDarkBlue">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="md:block p-3 px-6 pt-2 pb-2 text-white bg-brightGreen rounded-full baseline hover:bg-veryDarkBlue">
                        Log In
                    </a>
                @endauth
            @endif
        </div>

        <!--Hambuger Icon-->
        <button id="menu-btn" class="block hamburger md:hidden focus:outline-none">
            <span class="hamburger-top"></span>
            <span class="hamburger-middle"></span>
            <span class="hamburger-bottom"></span>
        </button>
    </div>
    <!--Mobile Menu-->
    <div class="md:hidden">
        <div id="menu" class="absolute flex-col items-center self-end hidden py-8 mt-10 space-y-6 font-bold bg-white sm:w-auto sm:self-center left-6 right-6 drop-shadow-md">
            <!-- <a href="#">Pricing</a>
            <a href="#">Product</a>
            <a href="#">About Us</a>
            <a href="#">Careers</a>
            <a href="#">Community</a> -->
            <!---Button -->
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="md:block p-3 px-6 pt-2 pb-2 text-white bg-brightGreen rounded-full baseline hover:bg-veryDarkBlue">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="md:block p-3 px-6 pt-2 pb-2 text-white bg-brightGreen rounded-full baseline hover:bg-veryDarkBlue">
                        Log In
                    </a>
                @endauth
            @endif
        </div>
    </div>
</nav>
<!--End Navbar -->
<!--Hero Section -->
<section id="hero">
    <div class="container flex flex-col-reverse md:flex-row items-center px-6 mx-auto mt-10 space-y-0 md:space-y-0">
        <div class="flex-col mb-32 space-y-12 md:w-1/2">
            <h1 class="max-w-md text-4xl font-bold text-center md:text-5l md:text-left">
                Bring everyone together to build better products
            </h1>
            <p class="max-w-sm text-center textdarkGrayishBlue md:text-left">
                K4Action makes it simple for teams to plan.
            </p>
            <!-- <div class="flex justify-center md:justify-start">
                <a href="#"
                   class="hidden md:block p-3 px-6 pt-2 text-white bg-darkBlue rounded-full baseline hover:bg-veryDarkBlue">
                    Get Started
                </a>
            </div> -->
        </div>
        <!---Image -->
        <div class="md:w-1/2">
            <img src="{{asset('assets/image/illustration.svg')}}" class="z-0" alt="illustration">

        </div>
    </div>

</section>

<!--Features Section-->
<section id="features">
    <div class="container flex flex-col px-4 mx-auto mt-10 space-y-12 md:space-y-0 md:flex-row">
        <div class="flex flex-col space-y-12 md:w-1/2">
            <h2 class="max-w-md text-4xl font-bold text-center md:text-left">
                What's different about K4Action?
            </h2>
            <div class="p max-w-sm text-center text-darkGrayishBlue md:text-left">
                K4Action is a platform that helps you get insight about data from our EMR.
            </div>
        </div>
        <div class="flex flex-col spacepace-y-8 md:w-1/2">
            <div class="flex flex-col space-y-3 md:space-y-0 md:space-x-6 md:flex-row">
                <div class="rounded l-full bg-brightGreenLight md:bg-transparent">
                    <div class="flex items-center space-x-2">
                        <div class="px-4 py-2 text-white font-bold text-center rounded-full md:py-1 bg-brightGreen">
                            01
                        </div>
                        <h3 class = "text-base font-bold md:mb-4 md:hidden">
                            Track PBS progress
                        </h3>
                    </div>
                </div>
                <div>
                    <h3 class="hidden mb-4 text-base font-bold md:block">
                        Track PBS progress
                    </h3>
                    <p class="text-darkGrayishBlue text-justify">
                        Get biometric coverage insight as an organization. Identify gaps by knowing how many clients
                        have their fingerprints captured, you can also see the number of clients with valid or invalid
                        prints. You can drill down to facility data.
                    </p><br>
                </div>
            </div>
            <div class="flex flex-col space-y-3 md:space-y-0 md:space-x-6 md:flex-row">
                <div class="rounded l-full bg-brightGreenLight md:bg-transparent">
                    <div class="flex items-center space-x-2">
                        <div class="px-4 py-2 text-white font-bold text-center rounded-full md:py-1 bg-brightGreen">
                            02
                        </div>
                        <h3 class = "text-base font-bold md:mb-4 md:hidden">
                           Treatment Monitoring
                        </h3>
                    </div>
                </div>
                <div>
                    <h3 class="hidden mb-4 text-base font-bold md:block">
                        Treatment Monitoring
                    </h3>
                    <p class="text-darkGrayishBlue text-justify">
                        See how your day-to-day tasks fit into the wider vision. Go from tracking progess to
                        at the milestone level all the way done to the smallest detail. Never lose track of the
                        bigger picture again.
                    </p><br>
                </div>
            </div>
            <div class="flex flex-col space-y-3 md:space-y-0 md:space-x-6 md:flex-row">
                <div class="rounded l-full bg-brightGreenLight md:bg-transparent">
                    <div class="flex items-center space-x-2">
                        <div class="px-4 py-2 text-white font-bold text-center rounded-full md:py-1 bg-brightGreen">
                            03
                        </div>
                        <h3 class = "text-base font-bold md:mb-4 md:hidden">
                            Appointment Reminders
                        </h3>
                    </div>
                </div>
                <div>
                    <h3 class="hidden mb-4 text-base font-bold md:block">
                        Appointment Reminders
                    </h3>
                    <p class="text-darkGrayishBlue text-justify">
                        Built-in service that send SMS reminders to clients, reminding them of their appointments, this
                        can either be ARV Refill or Sample Collection.
                    </p><br>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End of Features -->

<!--CTA Section-->
<section class="bg-brightBlue" id="cta">
    <div class="container flex flex-col items-center justify-between px-6 py-24 mx-auto space-y-12 md:py-12 md:flex-row md:space-y-0">
        <h2 class="text-5xl font-bold leading-tight text-center text-white md:text-4xl md:max-w-xl md:text-left">
            Start exploring with our user-friendly analytics platform
        </h2>
<!--        <div>
            <a href="#"
               class="p-3 px-6 pt-2 text-brightRed bg-white rounded-full shadow-2xl baseline hover:bg-gray-900">
                Get Started
            </a>
        </div>-->
    </div>

</section>

<!--Footer-->
<footer class="bg-veryDarkBlue">
    <!--Flex-->
    <div class="container flex flex-col-reverse justify-between px-6 py-10 mx-auto space-y-8 md:flex-row md:space-y-0">
        <!--Logo and Socials-->
        <div class="flex flex-col-reverse items-center justify-between space-y-12 md:flex-col md:space-y-0 md:items-start">
            <div class="mx-auto my-6 text-center text-white md:hidden">
                Copyright &copy;
                <script>
                    var CurrentYear = new Date().getFullYear()
                    document.write(CurrentYear)
                </script>
                APIN Health Informatics Team
            </div>
            <div>
                <img src="{{asset('assets/image/logo.jpeg')}}" class="block h-8" alt="Here">
            </div>
<!--            <div class="flex justify-center space-x-4">
                <a href="#">
                    <img src="./assets/img/icon-facebook.svg" alt="" class="h-8"/>
                </a>

                <a href="#">
                    <img src="./assets/img/icon-youtube.svg" alt="" class="h-8"/>
                </a>

                <a href="#">
                    <img src="./assets/img/icon-twitter.svg" alt="" class="h-8"/>
                </a>

                <a href="#">
                    <img src="./assets/img/icon-pinterest.svg" alt="" class="h-8"/>
                </a>

            </div>-->
        </div>
        <!--List Container-->
<!--        <div class="flex justify-around space-x-32">
            <div class="flex flex-col space-y-3 text-white">
                <a href="#" class="hover:text-brightRed">Home</a>
                <a href="#" class="hover:text-brightRed">Pricing</a>
                <a href="#" class="hover:text-brightRed">Products</a>
                <a href="#" class="hover:text-brightRed">About</a>
            </div>

            <div class="flex flex-col space-y-3 text-white">
                <a href="#" class="hover:text-brightRed">Careers</a>
                <a href="#" class="hover:text-brightRed">Community</a>
                <a href="#" class="hover:text-brightRed">Privacy Policy</a>
            </div>
        </div>-->

        <div class="flex flex-col justify-between">
<!--            <form>
                <div class="flex space-x-3">
                    <input type="text" class="flex-1 px-4 rounded-full focus:outline-none" placeholder="Updates in your inbox"/>
                    <button class="px-6 py-2 text-white rounded-full bg-brightRed hover:bg-brightRedLight focus:outline-none">
                        Go
                    </button>
                </div>
            </form>-->
            <div class="hidden text-white md:block">
                Copyright &copy;
                <script>
                    var CurrentYear = new Date().getFullYear()
                    document.write(CurrentYear)
                </script>
                 APIN Health Informatics Team
            </div>
        </div>
    </div>
</footer>
<script>
    const btn = document.getElementById('menu-btn');
    const nav = document.getElementById('menu');
    btn.addEventListener('click', () => {
        btn.classList.toggle('open')
        nav.classList.toggle('flex')
        nav.classList.toggle('hidden')
    });
</script>
</body>
</html>
