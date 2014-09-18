	@include('layout.header')	
	@yield('css')


</head>
<body>
	@include('layout.sidebar')
	@include('layout.navigation')
	@yield('content')
	@include('layout.footer')
	@yield('script')
	<script>
	    window.setTimeout(function() {
	        $(".alert").fadeTo(600, 0).slideUp(500, function(){
	            $(this).remove();
	        });
	    }, 5000);

	</script>
</body>
</html>