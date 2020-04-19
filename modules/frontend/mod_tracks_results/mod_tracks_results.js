/*
Add mootools tooltip event, with fading.
*/
window.addEvent('domready', function(){
   //do your tips stuff in here...
   var resultTip = new Tips($$('.resultTip'), {
      className: 'custom', //this is the prefix for the CSS class
      initialize:function(){
         this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 500, wait: false}).set(0);
      },
      onShow: function(toolTip) {
         this.fx.start(1);
      },
      onHide: function(toolTip) {
         this.fx.start(0);
      }
   });
});