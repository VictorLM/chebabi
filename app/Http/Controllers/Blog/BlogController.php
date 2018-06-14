<?php

namespace Intranet\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Intranet\Blog_Noticias;
use Illuminate\Support\Facades\Input;
use Jenssegers\Agent\Agent;

class BlogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'categoria', 'noticias', 'historias', 'artigo', 'comentar_artigo', 'historia', 'comentar_historia']]);
        $this->middleware('Intranet\Http\Middleware\Admin::class', ['except' => ['index', 'categoria', 'noticias', 'historias', 'artigo', 'comentar_artigo', 'historia', 'comentar_historia']]);
    }
    
    public function index(){
        $agent = new Agent();
        $artigos = DB::table('blog_artigos')->orderBy('created_at', 'DESC')->paginate(10);
        $noticias = Blog_Noticias::orderBy('publicacao', 'DESC')->limit(5)->get();
        $historias = DB::table('blog_historias')->orderBy('created_at', 'DESC')->limit(2)->get();
        $title = 'Artigos | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.blog.index', compact('title', 'noticias', 'artigos', 'historias', 'agent'));
    }

    public function categoria($categoria){
        $agent = new Agent();
        $artigos = DB::table('blog_artigos')
            ->where('categoria', $categoria)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $noticias = Blog_Noticias::orderBy('publicacao', 'DESC')->limit(5)->get();
        $historias = DB::table('blog_historias')->orderBy('created_at', 'DESC')->limit(2)->get();
        $title = 'Artigos | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.blog.index', compact('title', 'noticias', 'artigos', 'historias', 'agent'));
    }

    public function noticias(){
        $agent = new Agent();
        $artigos = DB::table('blog_artigos')->orderBy('created_at', 'DESC')->limit(3)->get();
        $noticias = Blog_Noticias::orderBy('publicacao', 'DESC')->limit(100)->paginate(15);
        $historias = DB::table('blog_historias')->orderBy('created_at', 'DESC')->limit(2)->get();
        $title = 'Notícias | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.blog.noticias', compact('title', 'noticias', 'artigos', 'historias', 'agent'));
    }

    public function historias(){
        $agent = new Agent();
        $artigos = DB::table('blog_artigos')->orderBy('created_at', 'DESC')->limit(3)->get();
        $noticias = Blog_Noticias::orderBy('publicacao', 'DESC')->limit(5)->get();
        $historias = DB::table('blog_historias')->orderBy('created_at', 'DESC')->paginate(5);
        $title = 'Histórias | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.blog.historias', compact('title', 'noticias', 'artigos', 'historias', 'agent'));
    }

    public function artigo($url){
        $artigo = DB::table('blog_artigos')->where('url', '/blog/artigos/'.$url)->first();

        if(!empty($artigo)){
            $agent = new Agent();

            $comentarios = DB::table('blog_artigos_comentarios')
                ->where('artigo', $artigo->id)
                ->orderBy('created_at', 'Desc')
                ->paginate(5);

            $noticias = Blog_Noticias::orderBy('publicacao', 'DESC')->limit(5)->get();

            $historias = DB::table('blog_historias')->orderBy('created_at', 'DESC')->limit(3)->get();

            $title = $artigo->titulo.' | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';

            return view('site.blog.artigo', compact('title', 'artigo', 'noticias', 'historias', 'comentarios', 'agent'));

        }else{
            abort(404);
        }

    }

    public function comentar_artigo(Request $request, $id){

        $validatedData = Validator::make($request->all(), [
            'nome' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'mensagem' => 'required|string|max:500',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        if (!$validatedData->fails()){

            $artigo = DB::table('blog_artigos')->find($id);

            if(!empty($artigo)){

                DB::table('blog_artigos_comentarios')->insert([
                    'autor' => strip_tags($request->nome),
                    'email' => $request->email,
                    'comentario' => strip_tags($request->mensagem),
                    'artigo' => $artigo->id,
                    'infos_sessao' => 'IP: ' . \Request::ip() .' - USER-AGENT: ' . $request->header('User-Agent'),
                    'created_at' => Carbon::now(),
                ]);
    
                $request->session()->flash('alert-success', 'Comentário enviado com sucesso! Obrigado por comentar.');
                return redirect($artigo->url);

            }else{
                return redirect()->back()
                    ->withInput()
                    ->withErrors(array('message' => 'Artigo não encontrado! Favor informar o erro pelo formulário de contato do site.'));
            }

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    public function historia($url){
        $historia = DB::table('blog_historias')->where('url', '/blog/historias/'.$url)->first();

        if(!empty($historia)){
            $agent = new Agent();
            $comentarios = DB::table('blog_historias_comentarios')
                ->where('historia', $historia->id)
                ->orderBy('created_at', 'Desc')
                ->paginate(5);

            $noticias = Blog_Noticias::orderBy('publicacao', 'DESC')->limit(5)->get();

            $artigos = DB::table('blog_artigos')->orderBy('created_at', 'DESC')->limit(3)->get();

            $title = $historia->titulo.' | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';

            return view('site.blog.historia', compact('title', 'artigos', 'noticias', 'historia', 'comentarios', 'agent'));

        }else{
            abort(404);
        }

    }

    public function comentar_historia(Request $request, $id){

        $validatedData = Validator::make($request->all(), [
            'nome' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'mensagem' => 'required|string|max:500',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        if (!$validatedData->fails()){

            $historia = DB::table('blog_historias')->find($id);

            if(!empty($historia)){

                DB::table('blog_historias_comentarios')->insert([
                    'autor' => strip_tags($request->nome),
                    'email' => $request->email,
                    'comentario' => strip_tags($request->mensagem),
                    'historia' => $historia->id,
                    'infos_sessao' => 'IP: ' . \Request::ip() .' - USER-AGENT: ' . $request->header('User-Agent'),
                    'created_at' => Carbon::now(),
                ]);
    
                $request->session()->flash('alert-success', 'Comentário enviado com sucesso! Obrigado por comentar.');
                return redirect($historia->url);

            }else{
                return redirect()->back()
                    ->withInput()
                    ->withErrors(array('message' => 'História não encontrada! Favor informar o erro pelo formulário de contato do site.'));
            }

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    //MÉTODOS DOS MIDDLEWARES

    /////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////MÉTODOS DOS ARTIGOS/////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////

    public function novo_artigo(){
        $title = 'Novo Artigo | Intranet Izique Chebabi Advogados';
        return view('site.blog.novo_artigo', compact('title'));
    }

    public function listar_artigos(){
        $artigos = DB::table('blog_artigos')->orderBy('created_at', 'DESC')->paginate(5);
        $title = 'Artigos | Intranet Izique Chebabi Advogados';
        return view('site.blog.listar_artigos', compact('title', 'artigos'));
    }

    public function editar_artigo(Request $request, $id){
        $artigo = DB::table('blog_artigos')->find($id);
        $title = 'Editar Artigo | Intranet Izique Chebabi Advogados';
        return view('site.blog.editar_artigo', compact('title', 'artigo'));
    }

    public function listar_comentarios_artigos(Request $request){
        $comentarios = DB::table('blog_artigos_comentarios')->where('artigo', $request->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $title = 'Comentarios do Artigo | Intranet Izique Chebabi Advogados';
        return view('site.blog.listar_comentarios_artigo', compact('title', 'comentarios'));
    }

    public function excluir_comentario_artigo(Request $request){

        $comentario = DB::table('blog_artigos_comentarios')->find($request->id);

        if(!empty($comentario)){

            DB::table('blog_artigos_comentarios')->where('id', $comentario->id)->delete();

            $request->session()->flash('alert-success', "Comentário excluído com sucesso!");
            return redirect()->action('Admin\AdminController@index');

        }else{
            return redirect()->back()
                ->withInput()
                ->withErrors(array('message' => 'Comentário não encontrado! Favor informar o erro pelo formulário de contato do site.'));
        }

    }

    public function inserir_artigo(Request $request){

        $validatedData = Validator::make($request->all(), [
            'titulo' => 'required|string|max:200',
            'autor' => 'required|string|max:100',
            'categoria' => 'required|in:Cível,Trabalhista,Outros',
            'tags' => 'required|string|max:200',
            'descricao' => 'required|string|min:100|max:5000',
            'imagem' => 'nullable|image|max:1000',
        ]);

        if (!$validatedData->fails()){

            if ($request->hasFile('imagem')){
                $nome = 'foto_artigo_'.date('dmYHis').'.'.Input::file('imagem')->getClientOriginalExtension();
                $request->file('imagem')->storeAs('/imagens/artigos', $nome, 'public');
                $imagem_path = "assets/imagens/artigos/".$nome;
            }else{
                $imagem_path = null;
            }

            DB::table('blog_artigos')->insert([
                'titulo' => $request->titulo,
                'autor' => $request->autor,
                'categoria' => $request->categoria,
                'tags' => $request->tags,
                'descricao' => $request->descricao,
                'url' => '/blog/artigos/'.str_limit(str_slug($request->titulo, "-"), 150),
                'imagem' => $imagem_path,
                'created_at' => Carbon::now(),
            ]);

            $request->session()->flash('alert-success', "Artigo cadastrado com sucesso! <a target='_blank' href='/blog/artigos/".str_limit(str_slug($request->titulo, "-"), 150)."'>Clique aqui</a> para visualiza-lo no Blog.");
            return redirect()->action('Admin\AdminController@index');

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    public function update_artigo(Request $request){

        $validatedData = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'titulo' => 'required|string|max:200',
            'autor' => 'required|string|max:100',
            'categoria' => 'required|in:Cível,Trabalhista,Outros',
            'tags' => 'required|string|max:200',
            'descricao' => 'required|string|min:100|max:5000',
            'imagem' => 'nullable|image|max:1000',
        ]);

        if (!$validatedData->fails()){

            $artigo = DB::table('blog_artigos')->find($request->id);

            if(!empty($artigo)){

                if ($request->hasFile('imagem')){
                    $nome = 'foto_artigo_'.date('dmYHis').'.'.Input::file('imagem')->getClientOriginalExtension();
                    $request->file('imagem')->storeAs('/imagens/artigos', $nome, 'public');
                    $imagem_path = "assets/imagens/artigos/".$nome;
                    if(!empty($artigo->imagem)){
                        $foto = substr(strrchr($artigo->imagem, "/"), 1);
                        Storage::disk('public')->delete('/imagens/artigos/'.$foto);
                    }
                }else{
                    $imagem_path = $artigo->imagem;
                }
    
                DB::table('blog_artigos')->where('id', $request->id)
                    ->update([
                        'titulo' => $request->titulo,
                        'autor' => $request->autor,
                        'categoria' => $request->categoria,
                        'tags' => $request->tags,
                        'descricao' => $request->descricao,
                        'url' => '/blog/artigos/'.str_limit(str_slug($request->titulo, "-"), 150),
                        'imagem' => $imagem_path,
                        'updated_at' => Carbon::now(),
                    ]);
    
                $request->session()->flash('alert-success', "Artigo editado com sucesso! <a target='_blank' href='/blog/artigos/".str_limit(str_slug($request->titulo, "-"), 150)."'>Clique aqui</a> para visualiza-lo no Blog.");
                return redirect()->action('Admin\AdminController@index');

            }else{
                return redirect()->back()
                    ->withInput()
                    ->withErrors(array('message' => 'Artigo não encontrado! Favor informar o erro pelo formulário de contato do site.'));
            }

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    public function excluir_artigo(Request $request){

        $artigo = DB::table('blog_artigos')->find($request->id);

        if(!empty($artigo)){

            if(!empty($artigo->imagem)){
                $foto = substr(strrchr($artigo->imagem, "/"), 1);
                if(file_exists('../public/assets/imagens/artigos/'.$foto)){
                    Storage::disk('public')->delete('/imagens/artigos/'.$foto);
                }  
            }

            DB::table('blog_artigos_comentarios')->where('artigo', $artigo->id)->delete();
            DB::table('blog_artigos')->where('id', $artigo->id)->delete();

            $request->session()->flash('alert-success', "Artigo excluído com sucesso e os comentários vinculados à ele também!");
            return redirect()->action('Admin\AdminController@index');

        }else{
            return redirect()->back()
                ->withInput()
                ->withErrors(array('message' => 'Artigo não encontrado! Favor informar o erro pelo formulário de contato do site.'));
        }

    }

    /////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////MÉTODOS DAS HITÓRIAS/////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////
    
    public function novo_historia(){
        $title = 'Nova História | Intranet Izique Chebabi Advogados';
        return view('site.blog.nova_historia', compact('title'));
    }

    public function listar_historias(){
        $historias = DB::table('blog_historias')->orderBy('created_at', 'DESC')->paginate(5);
        $title = 'Histórias | Intranet Izique Chebabi Advogados';
        return view('site.blog.listar_historias', compact('title', 'historias'));
    }

    public function editar_historia(Request $request, $id){
        $historia = DB::table('blog_historias')->find($id);
        $title = 'Editar História | Intranet Izique Chebabi Advogados';
        return view('site.blog.editar_historia', compact('title', 'historia'));
    }

    public function listar_comentarios_historias(Request $request){
        $comentarios = DB::table('blog_historias_comentarios')->where('historia', $request->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $title = 'Comentarios da História | Intranet Izique Chebabi Advogados';
        return view('site.blog.listar_comentarios_historia', compact('title', 'comentarios'));
    }

    public function excluir_comentario_historia(Request $request){

        $comentario = DB::table('blog_historias_comentarios')->find($request->id);

        if(!empty($comentario)){

            DB::table('blog_historias_comentarios')->where('id', $comentario->id)->delete();

            $request->session()->flash('alert-success', "Comentário excluído com sucesso!");
            return redirect()->action('Admin\AdminController@index');

        }else{
            return redirect()->back()
                ->withInput()
                ->withErrors(array('message' => 'Comentário não encontrado! Favor informar o erro pelo formulário de contato do site.'));
        }

    }

    public function inserir_historia(Request $request){

        $validatedData = Validator::make($request->all(), [
            'titulo' => 'required|string|max:200',
            'link' => 'required|string|max:100',
            'tags' => 'required|string|max:200',
            'descricao' => 'required|string|min:100|max:8000',
        ]);

        if (!$validatedData->fails()){

            DB::table('blog_historias')->insert([
                'titulo' => $request->titulo,
                'link' => $request->link,
                'tags' => $request->tags,
                'descricao' => $request->descricao,
                'url' => '/blog/historias/'.str_limit(str_slug($request->titulo, "-"), 150),
                'created_at' => Carbon::now(),
            ]);

            $request->session()->flash('alert-success', "História cadastrada com sucesso! <a target='_blank' href='/blog/historias/".str_limit(str_slug($request->titulo, "-"), 150)."'>Clique aqui</a> para visualiza-la no Blog.");
            return redirect()->action('Admin\AdminController@index');

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    public function update_historia(Request $request){

        $validatedData = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'titulo' => 'required|string|max:200',
            'link' => 'required|string|max:100',
            'tags' => 'required|string|max:200',
            'descricao' => 'required|string|min:100|max:8000',
        ]);

        if (!$validatedData->fails()){

            $historia = DB::table('blog_historias')->find($request->id);

            if(!empty($historia)){

                DB::table('blog_historias')->where('id', $request->id)
                    ->update([
                        'titulo' => $request->titulo,
                        'link' => $request->link,
                        'tags' => $request->tags,
                        'descricao' => $request->descricao,
                        'url' => '/blog/historias/'.str_limit(str_slug($request->titulo, "-"), 150),
                        'updated_at' => Carbon::now(),
                    ]);
    
                $request->session()->flash('alert-success', "História editada com sucesso! <a target='_blank' href='/blog/historias/".str_limit(str_slug($request->titulo, "-"), 150)."'>Clique aqui</a> para visualiza-la no Blog.");
                return redirect()->action('Admin\AdminController@index');

            }else{
                return redirect()->back()
                    ->withInput()
                    ->withErrors(array('message' => 'História não encontrada! Favor informar o erro pelo formulário de contato do site.'));
            }

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }


    public function excluir_historia(Request $request){

        $historia = DB::table('blog_historias')->find($request->id);

        if(!empty($historia)){

            DB::table('blog_historias_comentarios')->where('historia', $historia->id)->delete();
            DB::table('blog_historias')->where('id', $historia->id)->delete();

            $request->session()->flash('alert-success', "História excluída com sucesso e os comentários vinculados à ela também!");
            return redirect()->action('Admin\AdminController@index');

        }else{
            return redirect()->back()
                ->withInput()
                ->withErrors(array('message' => 'História não encontrada! Favor informar o erro pelo formulário de contato do site.'));
        }

    }


}
