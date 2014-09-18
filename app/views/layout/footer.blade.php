 <!--[if !IE]> -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

        <!-- <![endif]-->

        <!--[if IE]>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <![endif]-->

        <!--[if !IE]> -->
        <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo URL::to('/'); ?>/public/js/jquery.min.js'>"+"<"+"/script>");
        </script>
        <!-- <![endif]-->

        <!--[if IE]>
        <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo URL::to('/'); ?>/public/js/jquery1x.min.js'>"+"<"+"/script>");
        </script>
        <![endif]-->
        <script type="text/javascript">
            if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo URL::to('/'); ?>/public/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
        </script>

        {{HTML::script('public/js/ace.min.js')}}


        <!--[if lte IE 8]>
        {{HTML::script('public/js/excanvas.min.js')}}
        <![endif]-->
       