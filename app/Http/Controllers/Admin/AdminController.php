<?php

namespace Intranet\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Intranet\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('Intranet\Http\Middleware\Admin::class');
    }
    
    public function index(){
        $title = 'Área do Administrador | Intranet Izique Chebabi Advogados Associados';
        return view('admin.index', compact('title'));
    }

    public function form_procedimento(){

        $title = 'Novo Procedimento | Intranet Izique Chebabi Advogados Associados';
        
        return view('admin.form_procedimento', compact('title'));
    }
    
    public function novo_procedimento(Request $request){

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'tipo' => [
                'required',
                Rule::in(['admin', 'adv', 'adm', 'fin', 'admjur', 'geral']),
            ],
            'arquivo' => 'required|mimes:pdf|max:5240',
        ]);

        if (!$validatedData->fails()){
            $path = 'intranet/pdf/procedimentos/'.$request->input('tipo');
            $request->file('arquivo')->storeAs($path, $request->name.'.pdf');

            DB::table('procedimentos')->insert([
                'name' => $request->name,
                'tipo' => $request->tipo,
                'link' => 'pdf/procedimentos/'.$request->tipo.'/'.$request->name.'.pdf',
                'created_at' => Carbon::now(),
            ]);
            $request->session()->flash('alert-success', 'Procedimento cadastrado com sucesso!');
            return redirect()->action('Admin\AdminController@index');
        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }
    
    public function show_procedimento(){

        $procedimentos = DB::table('procedimentos')
            ->orderBy('name')
            ->paginate(20);

        $title = 'Excluir Procedimento | Intranet Izique Chebabi Advogados Associados';
        
        return view('admin.delprocedimento', compact('title', 'procedimentos'));
    }
    
    public function del_procedimento(Request $request){
        $path = DB::table('procedimentos')->select('link')->where('id', $request->id)->first();
        Storage::disk('local')->delete('/intranet/'.$path->link);
        DB::table('procedimentos')->where('id', $request->id)->delete();
        $request->session()->flash('alert-success', 'Procedimento excluído com sucesso!');
        return redirect()->action('Admin\AdminController@index');
    }
    
    public function form_tutorial(){

        $title = 'Novo Tutorial | Intranet Izique Chebabi Advogados Associados';
        
        return view('admin.form_tutorial', compact('title', 'procedimentos'));
    }
    
    public function novo_tutorial(Request $request){

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:tutoriais',
            'arquivo' => 'required|mimes:pdf|max:5240',
        ]);

        if (!$validatedData->fails()){
            $path = 'intranet/pdf/tutoriais/';
            $request->file('arquivo')->storeAs($path, $request->name.'.pdf');

            DB::table('tutoriais')->insert([
                'name' => $request->name,
                'link' => 'pdf/tutoriais/'.$request->name.'.pdf',
                'created_at' => Carbon::now(),
            ]);
            $request->session()->flash('alert-success', 'Tutorial cadastrado com sucesso!');
            return redirect()->action('Admin\AdminController@index');
        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }
    
    public function show_tutorial(){

        $tutoriais = DB::table('tutoriais')
            ->orderBy('name')
            ->paginate(20);

        $title = 'Excluir Tutorial | Intranet Izique Chebabi Advogados Associados';
        
        return view('admin.deltutorial', compact('title', 'tutoriais'));
    }
    
    public function del_tutorial(Request $request){
        $path = DB::table('tutoriais')->select('link')->where('id', $request->id)->first();
        Storage::disk('local')->delete('/intranet/'.$path->link);
        DB::table('tutoriais')->where('id', $request->id)->delete();
        $request->session()->flash('alert-success', 'Tutorial excluído com sucesso!');
        return redirect()->action('Admin\AdminController@index');
    }
    
    public function form_tarifador(){

        $title = 'Novo Tarifador | Intranet Izique Chebabi Advogados Associados';
        
        return view('admin.form_tarifador', compact('title', 'tarifadores'));
    }
    
    public function novo_tarifador(Request $request){

        $validatedData = Validator::make($request->all(), [
            'cliente' => 'required|string|max:100|unique:tarifadores',
            'tel' => 'required|string|max:100',
            'imp' => 'required|string|max:100'
        ]);

        if (!$validatedData->fails()){

            DB::table('tarifadores')->insert([
                'cliente' => $request->cliente,
                'tel' => $request->tel,
                'imp' => $request->imp,
                'created_at' => Carbon::now(),
            ]);
            $request->session()->flash('alert-success', 'Tarifadores cadastrados com sucesso!');
            return redirect()->action('Admin\AdminController@index');
        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }
    
    public function show_tarifador(){

        $tarifadores = DB::table('tarifadores')
            ->orderBy('cliente')
            ->paginate(20);

        $title = 'Excluir Tarifador | Intranet Izique Chebabi Advogados Associados';
        
        return view('admin.deltarifador', compact('title', 'tarifadores'));
    }

    public function del_tarifador(Request $request){
        
        DB::table('tarifadores')->where('id', $request->id)->delete();
        $request->session()->flash('alert-success', 'Tarifadores excluídos com sucesso!');
        return redirect()->action('Admin\AdminController@index');
    }
    
    /*
    * FUNÇÃO PARA MIGRAÇÃO *
    public function seed(){
        
        $usuarios = array(
            //ARRAY DE USUÁRIOS À SEREM MIGRADOS AQUI
        );
        
        foreach ($usuarios as $user){
            DB::table('users')->insert([
                'name' => $user['nome'],
                'email' => $user['login'],
                'password' => bcrypt($user['senha']),
                'ativo' => TRUE,
                'tipo' => $user['tipo'],
                'nascimento' => $user['bday'],
                'telefone' => $user['tel'],
                'ramal' => $user['ramal'],
                'uaus' => 0,
                'last_login' => Carbon::now(),
            ]);
        }
    }
    */
}
