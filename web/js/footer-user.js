    $(document).ready(function(){

    function removeFooter() {
        $('#show-footer').remove();
        $('#reshow-footer').removeClass('sticky-footer');
        $('#reshow-footer').addClass('bottom-right');
        var html = `
        <div id="remove-footer" class="bottom-rigth">
        <div class="row">
            <div class="col-sm-1 col-md-1 col-md-offset-1">
                <div class="form-group">
                      <i id="small-footer" class="fa fa-clock-o fa-2x white pointer-cursor ft"></i>
                </div>
            </div>
        </div>
        </div>
        `
        $('#reshow-footer').append(html);
    }

    var enabled = localStorage.getItem('footer');
    if (enabled == "false" ){
        removeFooter();
    }

    $('#reshow-footer').on('click', '.glyphicon-remove', function(event) {
        localStorage.setItem('footer', "false");
        removeFooter();

    });
   $('#reshow-footer').on('click', '.ft', function(event) {
        event.preventDefault();
        localStorage.setItem('footer', "true");
        console.log('show');
        $('#show-footer').show();
        $('#remove-footer').remove();
        $('#reshow-footer').addClass('sticky-footer');
        $('#reshow-footer').removeClass('bottom-right');
      
        var html = `
            <div id="show-footer" class="row">

            <div class="col-sm-1 col-md-1 col-md-offset-1">
                <div class="form-group">
                    <div class="dropup">
                        <a  class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"> <i class="glyphicon glyphicon-user"></i>`+user+`<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="navigation"><a href="#"><i class="icon-cog"></i>`+userText+ `</a></li>
                            <li class="divider"></li>
                            <li class="navigation"><a href="/profile/edit"><i class="icon-off"></i>Editar Perfil</a></li>
                            <li class="navigation"><a href="/profile"><i class="icon-off"></i>Ver Perfil</a></li>
                            <li class="navigation"><a href="/logout"><i class="icon-off"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-1 col-md-1">
                <div class="form-group">
                    <div class="dropup">
                        <a  class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"> <i class="glyphicon glyphicon-cog"></i> Ajustes<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li style="text-align: left;"class="navigation"><a href="/puesto/"><i class="icon-off"></i>Ver Puesto</a></li>
                            <li class="navigation"><a href="/datosprestaciones/"><i class="icon-off"></i>Ver Datos <br> Prestaciones</a></li>
                        </ul>
                    </div>
                </div>
            </div>
          
            <div class="col-md-1 col-md-offset-8">
                <div class="form-group">
                        <i id ="hide-footer" class="glyphicon glyphicon-remove white gi-2x"></i>
                </div>
            </div>
          
        </div>`
     
          $('#reshow-footer').append(html);
        
    }); 
});