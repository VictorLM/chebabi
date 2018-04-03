$(document).ready(function(){

    if ($(window).width() < 960) {
        var placement = 'top';
    }else {
        var placement = 'left';
    }

    $('[data-toggle="popover1"]').popover({
        placement: placement,
        html: true,
        template: '<div class="popover container"><div class="arrow"></div><a class="list-group-item" href="/intranet/admin/blog-novo-artigo"><i class="glyphicon glyphicon-plus" style="font-size:1em!important;"></i> Novo Artigo</a><a class="list-group-item" href="/intranet/admin/blog-editar-artigo"><i class="glyphicon glyphicon-pencil" style="font-size:1em!important;"></i> Editar Artigos</a></div>'
    });

    $('[data-toggle="popover2"]').popover({
        placement: placement,
        html: true,
        template: '<div class="popover container"><div class="arrow"></div><a class="list-group-item" href="/intranet/admin/blog-nova-historia"><i class="glyphicon glyphicon-plus" style="font-size:1em!important;"></i> Nova História</a><a class="list-group-item" href="/intranet/admin/blog-editar-historia"><i class="glyphicon glyphicon-pencil" style="font-size:1em!important;"></i> Editar História</a></div>'
    });

    $('a#popov1').on('click', function(e) {e.preventDefault(); return true;});
    $('a#popov2').on('click', function(e) {e.preventDefault(); return true;});
});