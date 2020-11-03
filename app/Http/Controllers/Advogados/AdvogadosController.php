<?php

namespace Intranet\Http\Controllers\Advogados;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Intranet\User;
use Intranet\Advogados;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class AdvogadosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('Intranet\Http\Middleware\Admin::class');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $advs = Advogados::with('nome_usuario:id,name')->paginate(20);
        $title = 'Editar Advogados | Intranet Izique Chebabi Advogados Associados';
        return view('admin.advs.index', compact('title', 'advs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::leftJoin('advogados', function($join) {
            $join->on('users.id', '=', 'advogados.usuario');
          })
          ->whereNull('advogados.usuario')
          ->where('tipo', 'adv')
          ->where('ativo', TRUE)
          ->select('users.id', 'users.name')
          ->orderBy('users.name')
          ->get();
        
        $title = 'Novo Advogado | Intranet Izique Chebabi Advogados Associados';
        return view('admin.advs.create_edit', compact('title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $adv = new Advogados;
        
        $validator = Validator::make($request->all(),$adv->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if ($request->hasFile('fotoadv')){
                $ext = Input::file('fotoadv')->getClientOriginalExtension();
                
                $nome = DB::table('users')
                    ->select('name')
                    ->where('id', $request->usuario)
                    ->first();
                
                $nome = preg_replace('/[^a-zA-Z0-9-_\.]/','', $nome->name);

                $request->file('fotoadv')->storeAs('/imagens/advogados', $nome.'.'.$ext, 'public');
                $foto = "assets/imagens/advogados/".$nome.'.'.$ext;
            }else{
                $foto = "assets/imagens/advogados/avatar.png";
            }
            
            $request->request->add(['foto' => $foto]);
            
            Advogados::create($request->all());
            $request->session()->flash('alert-success', 'Advogado cadastrado com sucesso!');
            return redirect()->action('Admin\AdminController@index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $adv = Advogados::with('nome_usuario:id,name')->find($id);
        $title = 'Editar Advogado | Intranet Izique Chebabi Advogados Associados';
        return view('admin.advs.create_edit', compact('title', 'adv'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $adv = Advogados::with('nome_usuario:id,name')->find($id);

        $validator = Validator::make($request->all(), [
            'usuario' => 'required|integer|digits_between:1,3',
            'oab' => 'required|string|max:10',
            'texto' => 'required|string|max:500',
            'fotoadv' => 'nullable|image|max:300',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            if ($request->hasFile('fotoadv')){
                $ext = Input::file('fotoadv')->getClientOriginalExtension();

                $nome = preg_replace('/[^a-zA-Z0-9-_\.]/','', $adv->nome_usuario->name);

                $request->file('fotoadv')->storeAs('/imagens/advogados', $nome.'.'.$ext, 'public');
                $foto = "assets/imagens/advogados/".$nome.'.'.$ext;
            }else{
                $foto = $adv->foto;
            }

            $adv->oab = $request->oab;
            $adv->texto = $request->texto;
            $adv->foto = $foto;
            $adv->save();
            
            $request->session()->flash('alert-success', 'Advogado atualizado com sucesso!');
            return redirect()->action('Admin\AdminController@index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $adv = Advogados::with('nome_usuario:id,name')->find($id);
        if(!empty($adv->foto) && $adv->foto != "assets/imagens/advogados/avatar.png"){
            $foto = substr(strrchr($adv->foto, "/"), 1);
            Storage::disk('public')->delete('/imagens/advogados/'.$foto);
        }
        $adv->delete();
        $request->session()->flash('alert-success', 'Advogado excluído com sucesso!');
        return redirect()->action('Admin\AdminController@index');
    }
}
