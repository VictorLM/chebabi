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
use Intranet\Cliente;
use Illuminate\Support\Facades\Input;
use Intranet\Advogados;

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

    public function clientes_index(){
        $clientes = Cliente::with('advogado_civel_1','advogado_civel_2','advogado_civel_3','advogado_trab_1','advogado_trab_2','advogado_trab_3')
            ->orderBy('nome')
            ->paginate(10);
        $title = 'Clientes | Intranet Izique Chebabi Advogados Associados';
        return view('admin.clientes.index', compact('title', 'clientes'));
    }

    public function form_cliente(){
        $advs = Advogados::pluck('usuario')->all();
        $users = User::whereIn('id', $advs)
            ->where('ativo', TRUE)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
        $title = 'Novo Cliente | Intranet Izique Chebabi Advogados Associados';
        return view('admin.clientes.create_edit', compact('title','users'));
    }

    public function novo_cliente(Request $request){
        $cliente = new Cliente;
        $validator = Validator::make($request->all(),$cliente->rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if ($request->hasFile('logo')){
                $ext = Input::file('logo')->getClientOriginalExtension();
                $nome = preg_replace('/[^a-zA-Z0-9-_\.]/','', $request->nome);
                $request->file('logo')->storeAs('/imagens/logo-clientes', $nome.'.'.$ext, 'public');
                $logo = "assets/imagens/logo-clientes/".$nome.'.'.$ext;
            }else{
                $logo = null;
            }
            $cliente->nome = $request->nome;
            $cliente->logo = $logo;
            $cliente->ativo = true;
            if(!empty($request->adv_civel)){
                $i = 1;
                foreach($request->adv_civel as $adv_civel){
                    $cliente->{"adv_civel_".$i} = $adv_civel;
                    $i++;
                }
            }
            if(!empty($request->adv_trab)){
                $i = 1;
                foreach($request->adv_trab as $adv_trab){
                    $cliente->{"adv_trab_".$i} = $adv_trab;
                    $i++;
                }
            }
            $cliente->empresas = json_encode(explode(";",$request->empresas));
            if(!$cliente->save()){
                return abort(403, 'Erro ao salvar no banco de dados.');
            }
            $request->session()->flash('alert-success', 'Cliente cadastrado com sucesso!');
            return redirect()->action('Admin\AdminController@index');
        }
    }

    public function edit_cliente($id){
        $cliente = Cliente::with('advogado_civel_1:id,name,email,ramal','advogado_civel_2:id,name,email,ramal','advogado_civel_3:id,name,email,ramal','advogado_trab_1:id,name,email,ramal','advogado_trab_2:id,name,email,ramal','advogado_trab_3:id,name,email,ramal')
            ->where('ativo', true)
            ->find($id);

            $advs = Advogados::pluck('usuario')->all();
            $users = User::whereIn('id', $advs)
                ->where('ativo', TRUE)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

        $title = 'Editar Cliente | Intranet Izique Chebabi Advogados Associados';
        return view('admin.clientes.create_edit', compact('title', 'cliente', 'users'));
    }

    public function update_cliente(Request $request, $id){
        $cliente = Cliente::find($id);

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:200',
            'logo' => 'nullable|image|max:300',
            'adv_civel'  => 'nullable|array|max:3',
            'adv_trab'  => 'nullable|array|max:3',
            'empresas' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if ($request->hasFile('logo')){
                $ext = Input::file('logo')->getClientOriginalExtension();
                $nome = preg_replace('/[^a-zA-Z0-9-_\.]/','', $request->nome);
                $request->file('logo')->storeAs('/imagens/logo-clientes', $nome.'.'.$ext, 'public');
                $logo = "assets/imagens/logo-clientes/".$nome.'.'.$ext;
            }else{
                $logo = $cliente->logo;
            }
            $cliente->nome = $request->nome;
            $cliente->logo = $logo;
            $cliente->ativo = true;
            $cliente->updated_at = Carbon::now();
            if(!empty($request->adv_civel)){
                $i = 1;
                foreach($request->adv_civel as $adv_civel){
                    $cliente->{"adv_civel_".$i} = $adv_civel;
                    $i++;
                }
            }
            if(!empty($request->adv_trab)){
                $i = 1;
                foreach($request->adv_trab as $adv_trab){
                    $cliente->{"adv_trab_".$i} = $adv_trab;
                    $i++;
                }
            }
            $cliente->empresas = json_encode(explode(";",$request->empresas));
            if(!$cliente->save()){
                return abort(403, 'Erro ao salvar no banco de dados.');
            }
            $request->session()->flash('alert-success', 'Cliente alterado com sucesso!');
            return redirect()->action('Admin\AdminController@index');
        }
    }

    public function delete_cliente(Request $request, $id){
        $cliente = Cliente::find($id);
        $cliente->ativo = FALSE;
        if(!$cliente->save()){
            return abort(403, 'Erro ao salvar no banco de dados.');
        }
        if(!empty($cliente->logo)){
            $logo = substr(strrchr($cliente->logo, "/"), 1);
            Storage::disk('public')->delete('/imagens/logo-clientes/'.$logo);
        }
        $request->session()->flash('alert-success', 'Cliente escluído com sucesso!');
        return redirect()->action('Admin\AdminController@index');
    }

    //ARRUMAR OS PARAMETROS DE MES E ANO
    public function print_rank_uau(){
        $ranking = DB::table('uaus')
        ->join('users', 'uaus.para', '=', 'users.id')
        ->select('uaus.*', 'users.name', 'users.ativo')
        ->where('users.ativo', TRUE)
        ->orderBy('users.name', 'Asc');

        $semestre = 1;

        //1º SEMESTRE
        $ranking->whereYear('uaus.created_at', Carbon::now()->year);

        //2º SEMESTRE
        //$ranking->whereYear('uaus.created_at', Carbon::now()->year)
        //    ->whereMonth('uaus.created_at', '>', '6');

        $ranking = $ranking->get()->groupBy('name');
        
        //ORDEM ALFABÉTICA DEPOIS DE ORDENAR PELO COUNT DO GROUP BY
        $ranking_sorted = [];
        $count = 0;
        foreach($ranking as $key => $user){
            $ranking_sorted[$user->count()][]['name'] = $key;
            $count++;
        }

        //SORT BY MAIOR NUMERO UAUS
        krsort($ranking_sorted);

        //dd($ranking_sorted);
        $title = 'Uau | Intranet Izique Chebabi Advogados Associados';
        return view('admin.uaus', compact('title', 'ranking_sorted', 'semestre'));
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
