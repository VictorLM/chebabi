<?php

namespace Intranet\Http\Controllers\User;

use Intranet\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intranet\User;
use Intranet\Advogados;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('Intranet\Http\Middleware\Admin::class');
    }
    //RETORNA TABELA COM USUÁRIOS E BOTÕES COM OS LINKS DAS AÇÕES
    public function index(){
        $users = User::select('id', 'name', 'email', 'tipo', 'last_login', 'ativo')->orderBy('name')->paginate(20);
        $title = 'Editar Usuários | Intranet Izique Chebabi Advogados Associados';
        return view('admin.users.index', compact('title', 'users'));
    }
    //RETORNA O FORM COM OS DADOS PREENCHIDOS
    public function edit($id){
        $user = User::find($id);
        $title = 'Editar Usuário | Intranet Izique Chebabi Advogados Associados';
        return view('auth.register', compact('title', 'user'));
    }
    //ATUALIZA O CADASTRO DO USUÁRIO
    public function update(Request $request, $id){

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100',
            'ramal' => 'required|numeric|digits:3',
            'telefone' => 'max:16',
            'nascimento' => 'max:10',
            'tipo' => [
                'required',
                Rule::in(['admin', 'adv', 'adm', 'fin', 'admjur']),
            ]
        ]);
        if (!$validatedData->fails()){
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->tipo = $request->tipo;
            $user->ramal = $request->ramal;
            $user->telefone = $request->telefone;
            $user->nascimento = $request->nascimento;
            $user->updated_at = Carbon::now();
            if (!empty($request->password)){
                $user->password = bcrypt($request->password);
            }
            $user->save();
            $request->session()->flash('alert-success', 'Usuário atualizado com sucesso!');
            return redirect()->action('Admin\AdminController@index');
        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }
    //ATIVA O USUÁRIO QUE ESTAVA DESATIVADO
    public function active(Request $request, $id){
        User::find($id)->update(['ativo' => TRUE]);
        $request->session()->flash('alert-success', 'Usuário reativado com sucesso!');
        return redirect()->action('Admin\AdminController@index');
    }
    //INATIVA O USUÁRIO(NÃO EXCLUI POR CONTA DOS RELACIONAMENTOS) 
    public function destroy(Request $request, $id){
        User::find($id)->update(['ativo' => FALSE]);
        Advogados::where('usuario', $id)->delete();
        $request->session()->flash('alert-success', 'Usuário desativado com sucesso! Se houver um Advogado(a) vinculado a este usuário, ele também será desativado.');
        return redirect()->action('Admin\AdminController@index');
    }

}
