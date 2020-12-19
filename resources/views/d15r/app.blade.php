<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico">

    <title>D15r</title>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/d15r.css') }}" rel="stylesheet">
    <style>
        [x-cloak] { display: none; }
    </style>
</head>
<body class="relative">
    <!-- This example requires Tailwind CSS v2.0+ -->
<div class="bg-gray-50" x-data="{ open: false }">
  <div class="py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <nav class="relative flex items-center justify-between sm:h-10 md:justify-center" aria-label="Global">
        <div class="flex items-center flex-1 md:absolute md:inset-y-0 md:left-0">
          <div class="flex items-center justify-between w-full md:w-auto">
            <a href="/">
              <span class="text-3xl font-bold text-indigo-600">D15r</span>
            </a>
            <div class="-mr-2 flex items-center md:hidden">
              <button @click="open = true" type="button" class="bg-gray-50 rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" id="main-menu" aria-haspopup="true">
                <span class="sr-only">Open main menu</span>
                <!-- Heroicon name: menu -->
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div class="hidden md:flex md:space-x-10">
          <a href="#contact" class="font-medium text-gray-500 hover:text-gray-900">Kontakt</a>
          <a href="https://danielsundermeier.gitbook.io/knowledge/" class="font-medium text-gray-500 hover:text-gray-900">Notizen</a>
          <a href="https://github.com/danielsundermeier" class="font-medium text-gray-500 hover:text-gray-900">Github</a>
        </div>
      </nav>
    </div>

    <div x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition:enter="duration-150 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="duration-100 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
      <div class="rounded-lg shadow-md bg-white ring-1 ring-black ring-opacity-5">
        <div class="px-5 pt-4 flex items-center justify-between">
          <div>
              <a href="/">
                  <span class="text-3xl font-bold text-indigo-600">D15r</span>
              </a>
          </div>
          <div class="-mr-2">
            <button @click="open = false" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
              <span class="sr-only">Close menu</span>
              <!-- Heroicon name: x -->
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
        <div role="menu" aria-orientation="vertical" aria-labelledby="main-menu">
          <div class="px-2 pt-2 pb-3" role="none">
            <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50" role="menuitem">Kontakt</a>
            <a href="https://danielsundermeier.gitbook.io/knowledge/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50" role="menuitem">Notizen</a>
            <a href="https://github.com/danielsundermeier" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50" role="menuitem">Github</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="py-16 bg-white">
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="text-lg max-w-prose mx-auto text-center">
      <h1>
        <span class="mt-2 block text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Hi, ich bin Daniel</span>
      </h1>
      <p class="mt-8 text-xl text-gray-500 leading-8">Es macht mir Spaß mein Leben mit Software und Automatisierungen zu vereinfachen.</p>
    </div>
  </div>
</div>

<div class="bg-white">
  <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-24 lg:px-8 lg:grid lg:grid-cols-3 lg:gap-x-8">
    <div>
      <h2 class="text-base font-semibold text-indigo-600 uppercase tracking-wide">Programmieren</h2>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">Auswahl meiner Projekte</p>
      <p class="mt-4 text-lg text-gray-500"></p>
    </div>
    <div class="mt-12 lg:mt-0 lg:col-span-2">
      <dl class="space-y-10 sm:space-y-0 sm:grid sm:grid-cols-2 sm:grid-rows-2 sm:grid-flow-col sm:gap-x-6 sm:gap-y-10 lg:gap-x-8">
        <div class="flex">
          <div class="ml-3">
            <dt class="text-lg leading-6 font-medium text-gray-900">
              <a href="https://www.serienguide.tv" target="_blank">serienguide.tv</a>
            </dt>
            <dd class="mt-2 text-base text-gray-500">
              Markiere deine gesehenen Filme & Episoden als gesehen, verwalte Listen und folge deinen Freunden.
            </dd>
          </div>
        </div>

        <div class="flex">
          <div class="ml-3">
            <dt class="text-lg leading-6 font-medium text-gray-900">
              <a href="https://www.rechnungspilot.de" target="_blank">Rechnungspilot</a>
            </dt>
            <dd class="mt-2 text-base text-gray-500">
              Verwalte deine Firma, CRM, Rechnungen schreiben, etc.
            </dd>
          </div>
        </div>

        <div class="flex">
          <div class="ml-3">
            <dt class="text-lg leading-6 font-medium text-gray-900">
              <a href="https://www.cardmonitor.de" target="_blank">Cardmonitor</a>
            </dt>
            <dd class="mt-2 text-base text-gray-500">
              Verwaltung für dein Carmarket Konto.
              Artikel verwalten, Bestellungen abwickeln und Preise aktualsieren.
            </dd>
          </div>
        </div>

        <div class="flex">
          <div class="ml-3">
            <dt class="text-lg leading-6 font-medium text-gray-900">
              <a href="https://github.com/LifeOS-HQ" target="_blank">LifeOS</a>
            </dt>
            <dd class="mt-2 text-base text-gray-500">
              Aggregieren von Daten aus meinem Leben.
            </dd>
          </div>
        </div>
      </dl>
    </div>
  </div>
</div>

<div class="bg-gray-300">
  <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-24 lg:px-8 lg:grid lg:grid-cols-3 lg:gap-x-8">
    <div>
      <h2 class="text-base font-semibold text-indigo-600 uppercase tracking-wide">Automation</h2>
      <p class="mt-2 text-3xl font-extrabold text-gray-900">Tools, die ich nutze</p>
      <p class="mt-4 text-lg text-gray-500"></p>
    </div>
    <div class="mt-12 lg:mt-0 lg:col-span-2">
      <dl class="space-y-10 sm:space-y-0 sm:grid sm:grid-cols-2 sm:grid-rows-1 sm:grid-flow-col sm:gap-x-6 sm:gap-y-10 lg:gap-x-8">
        <div class="flex">
          <div class="ml-3">
            <dt class="text-lg leading-6 font-medium text-gray-900">
              <a href="https://www.home-assistant.io/" target="_blank">Home Assistant</a>
            </dt>
            <dd class="mt-2 text-base text-gray-500">
              Open source home automation that puts local control and privacy first.
            </dd>
          </div>
        </div>

        <div class="flex">
          <div class="ml-3">
            <dt class="text-lg leading-6 font-medium text-gray-900">
              <a href="https://esphome.io/" target="_blank">ESPHome</a>
            </dt>
            <dd class="mt-2 text-base text-gray-500">
              Firmaware für ESP8266, 32.
            </dd>
          </div>
        </div>
      </dl>
  </div>
</div>

<div id="contact" class="relative bg-white">
  <div class="absolute inset-0">
    <div class="absolute inset-y-0 left-0 w-1/2 bg-gray-50"></div>
  </div>
  <div class="relative max-w-7xl mx-auto lg:grid lg:grid-cols-5">
    <div class="bg-gray-50 py-16 px-4 sm:px-6 lg:col-span-2 lg:px-8 lg:py-24 xl:pr-12">
      <div class="max-w-lg mx-auto">
        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">
          Sag Hallo
        </h2>
        <p class="mt-3 text-lg leading-6 text-gray-500">
          Ich unterstütze dich gerne bei der Programmierung oder Automatisierung von Abläufen!
        </p>
      </div>
    </div>
    <div class="bg-white py-16 px-4 sm:px-6 lg:col-span-3 lg:py-24 lg:px-8 xl:pl-12">
      <div class="max-w-lg mx-auto lg:max-w-none">
        <form action="/contact" method="POST" class="grid grid-cols-1 gap-y-6">
          @csrf

          <div>
            <label for="name" class="sr-only">Name</label>
            <input type="text" name="name" id="name" autocomplete="name" class="block w-full shadow-sm py-3 px-4 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" placeholder="Name">
            @error('name')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="mail" class="sr-only">E-Mail</label>
            <input id="mail" name="mail" type="email" autocomplete="email" class="block w-full shadow-sm py-3 px-4 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" placeholder="E-Mail">
            @error('mail')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="message" class="sr-only">Nachricht</label>
            <textarea id="message" name="message" rows="4" class="block w-full shadow-sm py-3 px-4 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" placeholder="Nachricht"></textarea>
            @error('message')
                <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Abschicken
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- This example requires Tailwind CSS v2.0+ -->
<footer class="bg-gray-800">
  <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8 text-center text-base text-gray-400">
    <div class="flex justify-center space-x-6 md:order-2">
        <a href="/impressum">Impressum</a>
    </div>
    <div class="mt-8 md:mt-0 md:order-1">
      <p class="">
      </p>
    </div>
  </div>
</footer>

@if (Session::has('status'))
    <div id="notification" class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
      <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="p-4">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <!-- Heroicon name: check-circle -->
              <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
              <p class="text-sm font-medium text-gray-900">
                Nachricht verschickt!
              </p>
              <p class="mt-1 text-sm text-gray-500">
                Vielen Dank, ich melde mich.
              </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
              <button onclick="document.getElementById('notification').remove();" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span class="sr-only">Close</span>
                <!-- Heroicon name: x -->
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
@endif

</body>
</script>