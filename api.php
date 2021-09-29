<?php

if(isset($_POST['cnpj']) && !empty($_POST['cnpj'])){
    $cnpj = $_POST['cnpj'];
}
else
if(isset($_GET['cnpj']) && !empty($_GET['cnpj'])){
    $cnpj = $_GET['cnpj'];
}
else{
    exit('Nenhum CNPJ valido informado');
}

$dados = file_get_contents('https://www.receitaws.com.br/v1/cnpj/'.$cnpj);
$array = array();

$lista = ['fantasia', 'nome', 'abertura','natureza_juridica','situacao','qual','capital_social','porte','logradouro','numero','complemento','bairro','municipio','uf','cep','telefone','email'];

$atividade = explode('"atividade_principal":[{"text":"',$dados)[1];
$array['atividade'] = explode('"',$atividade)[0];

foreach($lista as $item){
    $aux = explode('"'.$item.'":"',$dados)[1];
    $array[$item] = explode('"',$aux)[0];
}

$aux = explode('"nome":"',$dados);
$total = count($aux);

for($i = 2; $i < $total; $i++){
    $aux = explode('"nome":"',$dados)[$i];
    $array['socios'][$i-1] = explode('"',$aux)[0];
}

$resultado = '';

foreach($array as $key => $value){
    if($key != 'socios'){
        $resultado .= '<div class="form-group">';
        if($key == 'logradouro' || $key == 'numero' || $key == 'complemento'){
            $endereco .= $value.' ';
            if($key == 'complemento'){
                $resultado .= '<label for="exampleInputEndereco">Endereço</label>';
                $resultado .= '<input type="text" class="form-control" id="exampleInputEndereco" value="'.ucwords($endereco).'" placeholder="" name="endereco">';
                $resultado .= '</div>';
            }
        }
        else{
            $resultado .= '<label for="exampleInput'.$key.'">'.ucwords($key).'</label>';
            $resultado .= '<input type="text" class="form-control" id="exampleInput'.$key.'" value="'.ucwords($value).'" placeholder="" name="'.$key.'">';
            $resultado .= '</div>';
        }
    }
}

echo($resultado);

// $json = json_encode($array);
// echo($json);
// print_r(json_decode($json));
?>