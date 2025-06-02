<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#17a2b8">
    <title>{{$meta['title']}}</title>
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    {{-- <link rel="stylesheet" href="/css/app.css"> --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- pdf froala --}}
    <script type="text/javascript" src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{-- brand icon --}}

    <link rel="icon" type="image/png" href="{{ asset('storage/masjid-dummi.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/masjid-dummi.png') }}">

<!-- font untuk froala -->
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,700,700italic&subset=latin,vietnamese,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Oswald:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700&subset=latin,greek,greek-ext,vietnamese,cyrillic-ext,cyrillic,latin-ext' rel='stylesheet' type='text/css'>

<link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Birthstone&family=Damion&family=Fleur+De+Leah&family=Norican&family=Paprika&family=Titan+One&family=Yaldevi:wght@200&display=swap" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">


<link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@1,300&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Carattere&family=Chela+One&family=Courgette&family=Fleur+De+Leah&family=Licorice&family=Mr+De+Haviland&family=Oooh+Baby&family=Pushster&family=Tinos&family=WindSong&display=swap" rel="stylesheet"> 

<!-- //font jilid2 -->
<link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Bilbo+Swash+Caps&family=Black+Han+Sans&family=Dancing+Script&family=DynaPuff&family=Leckerli+One&family=Lily+Script+One&family=Montserrat+Alternates:wght@500&family=Ms+Madi&family=Oleo+Script+Swash+Caps&family=Orbitron&family=Pacifico&family=Redressed&family=Wendy+One&display=swap" rel="stylesheet"> 


<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{$meta['url']}}">
<meta property="og:title" content="{{$meta['title']}}">
<meta property="og:description" content="{{$meta['description']}}">
<meta property="og:image" content="{{$meta['image']}}">
<meta property="og:image:width" content="300">
<meta property="og:image:height" content="300">	
<meta property="og:image:type" content="image/jpg">
<!-- Twitter -->
<meta property="twitter:card" content="{{$meta['url']}}">
<meta property="twitter:url" content="{{$meta['title']}}">
<meta property="twitter:title" content="{{$meta['description']}}">
<meta property="twitter:description" content="{{$meta['description']}}">
<meta property="twitter:image" content="{{$meta['image']}}">

    
<style>

 
div.fr-wrapper>div>a {
    font-size: 1px !important;
    padding: 1px !important;
    height: 1px !important;
    pointer-events: none;
  cursor: default;
    text-decoration: none;
}


[data-f-id="pbf"] {
        /* position: fixed; */
        /* z-index: -99999 !important; */
        font-size: 0px !important;
    padding: 0px !important;
    height: 0px !important;
    margin-top : 0px !important;
      display: none !important;
opacity: 0;
}

a#fr-logo {
  display: none !important;
        /* position: fixed; */
        /* z-index: -99999 !important; */
        font-size: 0px !important;
    padding: 0px !important;
    height: 0px !important;
}

.class2  tr td:nth-child(2) {
  border-left: 3px solid black;
  border-right: 3px solid black;
  padding : 8px;
}
.class1  tr td:nth-child(2) {
  border-left: 3px solid white;
  border-right: 3px solid white;
  padding : 8px;
}

/* hilangkan garis */
.class3 tbody tr td {
  border: none;
}

.class4  tr td:nth-child(1) {  
  border-right: 3px solid white;
  padding : 8px;
}


.class5  tr td:nth-child(1) {  
  border-right: 3px solid black;
  padding : 8px;
}

.fullKan {
  width: 100% !important;
}

/* buka editor langsung arial dan uk font 16pt */
.fr-box.fr-basic .fr-element {
  font-family: Arimo,  sans-serif!important;
  font-size: 18pt !important;
}

@media (min-width: 992px) {
  .fr-box.fr-document .fr-wrapper .fr-element {
  
    background: #FFF;
    width: 23cm !important;
    margin: auto;
    min-height: 32cm !important;
    padding: 0.6cm 0.6cm !important;
    overflow: visible;
    z-index: auto;
    padding-top: 0cm !important;
  }
}

@media print {
  .fr-box.fr-document .fr-wrapper .fr-element {
    
    background: #FFF;
    width: 23cm !important;
    margin: auto;
    min-height: 32cm !important;
    padding: 1cm 1cm !important; 
    overflow: visible;
    z-index: auto;
    padding-top: 0cm !important;
   
  }

      .fr-dib {
      float: none;
      display: block;
      margin: 5px auto;     
      margin-top: -36px !important;
    }
   
}

  </style>

</head>
<body>
<div id="app">
       <app></app>
</div>

{{-- <script src="/js/app.js"></script>  --}}
<script src="{{ asset('js/app.js') }}" defer></script> 


</body>

</html>
