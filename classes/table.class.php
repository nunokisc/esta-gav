<?php

//declaração da classe tabela
class table {

    protected $ligacao;
    public $colunas_obrigatorias=array();
    public $colunas=array();
    public $joins=array();
    public $joinTables=array();
    public $selects=array();
    public $sets=array();
    protected $i=0;
    protected $debug=0;
    protected $tabela="";
    protected $primarykey="";

    public function __construct() {
        $this->ligacao=mysqli_connect("localhost","root","Nuno79633410.","esta_av_system");
    }
    public function __destruct() {
        mysqli_close($this->ligacao);
    }
    public function select($selects=array(),$joins=array(),$joinTables=array(),$colunas=array(),$format="") {
        $query="select ";
        $selected=array();
        if (sizeof($selects)>0 && $selects != '') {
            foreach($selects as $c) {
                $selected[]=$c;        
            }
            $query.=implode(", ", $selected);
        }else{
            $query.="*";
        }
        $query .= " from ". $this->tabela;
        if (sizeof($joins)>0 && $joins != '') {
        	if (sizeof($joinTables)>0 && $joinTables != '') {
        		$innerjoinTables=array();
        		foreach($joinTables as $c=>$v) {
                	$innerjoinTables[]="$c = $v";
            	}
	            foreach($joins as $c) {
	                $query.=" INNER JOIN $c ON ".$innerjoinTables[$this->i];
	                $this->i++;
	            }
                $this->i = 0;
        	}
        }
        if (sizeof($colunas)>0 && $colunas != '') {
            $query.=" WHERE ";
            $ANDs=array();
            foreach($colunas as $c=>$v) {
                $ANDs[]="$c = $v";
            }
            $query.=implode(" AND ", $ANDs);
        }
        if($this->debug == 1){
            echo $query.' | ';
        }
        $linhas=mysqli_query($this->ligacao,$query);

        if ($format=="MYSQLI_ASSOC"){
            return mysqli_fetch_array($linhas);
        
        }elseif($format=="MYSQLI_ROW") {
            return mysqli_fetch_row($linhas);
        }
        else{
            return $linhas; //todas as linhas
        }
    }
    public function insert($colunas=array()) {
        $colunas= array_merge($this->colunas,$colunas); //junção de arrays
        $erros="";
        foreach($this->colunas_obrigatorias as $c) {
            if ($colunas[$c]=="") {
                $erros.= "$c está por preencher; "; //a função retorna false
            }
        }
        if ($erros) 
        return $erros;

        $nomeColunas=array();
        $valorColunas=array();
        foreach($colunas as $coluna=>$valor) {
            //acumular os nomes ou valores das colunas nos respetivos arrays
            $nomeColunas[]=$coluna;
            $valorColunas[]=$valor;

        }

        $sql="INSERT into ".$this->tabela." (".implode(",", $nomeColunas).") 
                values ('".implode("','", $valorColunas)."') ";

        //echo $sql;
        $resultado=mysqli_query($this->ligacao, $sql); // die(mysqli_error($this->ligacao));

        return $resultado;
       
    }
    public function update($sets=array(), $colunas=array()) {
        if (sizeof($colunas)==0) return "Deve atualizar pelo menos uma coluna";

        $sql="UPDATE ".$this->tabela." SET ";

        //ciclo para iterar as colunas a atualizar
        $sets2=array();
        foreach($sets as $coluna=>$valor) {

            $sets2[]="$coluna='$valor'";

        }
        $sql.=implode(", ", $sets2);  //transformar o array numa string, usando um separador
        //$sql=substr($sql,0,strlen($sql)-1);

        //filtro colunas a atualizar
        $sql.=" WHERE ";

        $colunas2=array();
        foreach($colunas as $c=>$v) {

            $colunas2[]="$c='$v'";

        }
        $sql.=implode(" AND ", $colunas2);
       //echo $sql;
        mysqli_query($this->ligacao, $sql) or die(mysqli_error($this->ligacao));

        return TRUE;
       
    }  
    // public function simpleDelete($id) {
    //     if (!$id>0) return "A chave é obrigatória";
        
    //     $sql="DELETE from produtos  WHERE idproduto=".$id;

    //     mysqli_query($this->ligacao, $sql) or die(mysqli_error($this->ligacao));

    //     return TRUE;
       
    // }        
    
    public function delete($colunas=array()) {

        if (sizeof($colunas)==0) return "Deve indicar pelo menos uma coluna";
        
        $sql="DELETE from ". $this->tabela."  WHERE ";
        $colunas2=array();


        foreach($colunas as $coluna=>$valor) {

            $colunas2[]="$coluna='$valor'";

        }
        echo $colunas2[0];
        $sql.=implode(" AND ", $colunas2);


        mysqli_query($this->ligacao, $sql) or die(mysqli_error($this->ligacao));

        return TRUE;
       
    }        
        
   
}
