@extends('d15r.app')

@section('content')

    <div class="py-16 bg-white">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="text-lg max-w-prose mx-auto text-center">
          <h1>
            <span class="mt-2 block text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Hi, ich bin Daniel</span>
          </h1>
          <p class="mt-8 text-xl text-gray-500 leading-8">
            Es macht mir Spaß mein Leben mit <a href="#software" class="underline hover:text-gray-500 hover:bg-gray-100">Software</a> und <a href="#automation" class="underline hover:text-gray-500 hover:bg-gray-100">Automatisierungen</a> zu vereinfachen.
            Ich möchte alles automatisieren, damit sich jeder auf seine Leidenschaft konzentrieren kann (<a href="https://danielsundermeier.gitbook.io/knowledge/masterplan/masterplan" class="underline hover:text-gray-500 hover:bg-gray-100">Masterplan</a>).
            Ich versuche mein <a href="https://danielsundermeier.gitbook.io/knowledge/leben/leben" class="underline hover:text-gray-500 hover:bg-gray-100">Leben</a> so einfach und angenehm wie möglich zu gestalten.
            Ich veröffentliche meine Gedanken in meinen <a href="https://danielsundermeier.gitbook.io/knowledge/" class="underline hover:text-gray-500 hover:bg-gray-100">Notizen</a>. Du bist herzlich eingeladen mit mir darüber zu <a href="#contact" class="underline hover:text-gray-500 hover:bg-gray-100">diskutieren</a>.
            Meine anderen Projekte findest Du bei <a href="https://github.com/danielsundermeier" class="underline hover:text-gray-500 hover:bg-gray-100">Github</a>.
          </p>
        </div>
      </div>
    </div>

    <div class="bg-white" id="software">
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

    <div class="bg-gray-300" id="automation">
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

@endsection