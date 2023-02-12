/*
Todo: Remove dependancy of hoverIntent
*/


jQuery(function( $ ) {
    
    
    
    $.fn.Megadropdown = function(options) {
        
        var settings = $.extend({
        activeClass: 'open',
        fadeInDuration: 250,
        fadeOutDuration: 'slow',
        openAnimation: 'fadeInUp',
        closeAnimation: 'fadeOutDown',
        hoverTimeout: 450
        
        }, options );
        
        
        
        function openmenu()
        {
            
        $menu = $(this);
          $menu.addClass(settings.activeClass)
               .find('ul')
               .stop(true, true)
               .fadeIn(settings.fadeInDuration)
               .addClass('animated')
               .removeClass(settings.closeAnimation)
               .addClass(settings.openAnimation, function(){
                   this.addClass('navvisfix')
               });
       }
      
      function closemenu()
      {
          $menu = $(this);
          $menu.removeClass(settings.activeClass)
                .find('ul')
                .stop(true, true).fadeOut(settings.fadeOutDuration)
                .removeClass(settings.openAnimation)
                .addClass(settings.closeAnimation).removeClass('navvisfix');
      }
        
        $nav = this;
        
        //Fallback, remove css hover classes.
        $nav.removeClass('nojs').addClass('dsmenu');
        //Animate the menu using jQuery
        
        if (!$.fn.hoverIntent) {
                // Hover Intent not found so use the standard jquery hover
                // Todo: Add CSS delay to simulate hoverIntent in newer browsers - settings.delay
                $('.dsmenu li.item').hover({over : openmenu, out : closemenu});  
        }else{
            //Found HoverIntent so use it
            $('.dsmenu li.item').hoverIntent({over : openmenu, out : closemenu, timeout : settings.hoverTimeout});  
        }
        return this;
    };
    
  
    
}(jQuery));
