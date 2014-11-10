;(function() {
  // Polyfill for getComputedStyle
  window.getComputedStyle||(window.getComputedStyle=function(a){return this.el=a,this.getPropertyValue=function(b){var c=/(\-([a-z]){1})/g;return"float"==b&&(b="styleFloat"),c.test(b)&&(b=b.replace(c,function(){return arguments[2].toUpperCase()})),a.currentStyle[b]?a.currentStyle[b]:null},this});

  // Polyfills for querySelector/querySelectorAll
  if(!document.querySelectorAll){document.querySelectorAll=function(selectors){var style=document.createElement('style'),elements=[],element;document.documentElement.firstChild.appendChild(style);document._qsa=[];style.styleSheet.cssText=selectors+'{x-qsa:expression(document._qsa && document._qsa.push(this))}';window.scrollBy(0,0);style.parentNode.removeChild(style);while(document._qsa.length){element=document._qsa.shift();element.style.removeAttribute('x-qsa');elements.push(element)}document._qsa=null;return elements}}if(!document.querySelector){document.querySelector=function(selectors){var elements=document.querySelectorAll(selectors);return(elements.length)?elements[0]:null}}

  // classList polyfill, by Remy Sharp (https://github.com/remy/polyfills)
  (function(){if(typeof window.Element==="undefined"||"classList"in document.documentElement)return;var prototype=Array.prototype,push=prototype.push,splice=prototype.splice,join=prototype.join;function DOMTokenList(el){this.el=el;var classes=el.className.replace(/^\s+|\s+$/g,'').split(/\s+/);for(var i=0;i<classes.length;i++){push.call(this,classes[i])}};DOMTokenList.prototype={add:function(token){if(this.contains(token))return;push.call(this,token);this.el.className=this.toString()},contains:function(token){return this.el.className.indexOf(token)!=-1},item:function(index){return this[index]||null},remove:function(token){if(!this.contains(token))return;for(var i=0;i<this.length;i++){if(this[i]==token)break}splice.call(this,i,1);this.el.className=this.toString()},toString:function(){return join.call(this,' ')},toggle:function(token){if(!this.contains(token)){this.add(token)}else{this.remove(token)}return this.contains(token)}};window.DOMTokenList=DOMTokenList;function defineElementGetter(obj,prop,getter){if(Object.defineProperty){Object.defineProperty(obj,prop,{get:getter})}else{obj.__defineGetter__(prop,getter)}}defineElementGetter(Element.prototype,'classList',function(){return new DOMTokenList(this)})})();

  function fwe_thumbnail_click() {

    var embedel;

    if( this.classList.contains( 'fwe_embed-youtube' ) ) {
      embedel = document.createElement( "iframe" );
      embedel.setAttribute( "class", "fwe_embed-box" );
      embedel.setAttribute( "src", "https://www.youtube.com/embed/" + this.id + "?autoplay=1&autohide=1&border=0&wmode=opaque&enablejsapi=1" );
    }

    else if( this.classList.contains( 'fwe_embed-vimeo' ) ) {
      embedel = document.createElement( "iframe" );
      embedel.setAttribute( "class", "fwe_embed-box" );
      embedel.setAttribute( "src", "//player.vimeo.com/video/" + this.id + "?autoplay=1" );
    }

    else if( this.classList.contains( 'fwe_embed-wordpresstv' ) ) {
      embedel = document.createElement( "embed" );
      embedel.setAttribute( "class", "fwe_embed-box" );
      embedel.setAttribute( "type", "application/x-shockwave-flash");
      embedel.setAttribute( "flashvars", "guid=GmPDhkyi&autoPlay=true" );
      embedel.setAttribute( "src", "http://s0.videopress.com/player.swf?v=1.03" );
    }

    embedel.style.width = window.getComputedStyle(this).getPropertyValue( "width" );
    embedel.style.height = window.getComputedStyle(this).getPropertyValue( "height" );
    embedel.style.display = window.getComputedStyle(this).getPropertyValue( "display" );
    embedel.style.margin = window.getComputedStyle(this).getPropertyValue( "margin" );
    this.parentNode.replaceChild(embedel, this);
  }

  var fwe_embedels = document.querySelectorAll( '.fwe_embed' );
  for( var fwe=0; fwe < fwe_embedels.length; fwe++ ) {
    fwe_embedels[fwe].onclick = fwe_thumbnail_click;
  }

})();
