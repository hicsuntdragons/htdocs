<?php
class visitas {
		public $num_visitas;
	public function __construct (){
		// Pegamos o IP do visitante
		$ip = getenv('REMOTE_ADDR');
		// Esta variavel será usada para que caso o IP ja exista ela receba outro valor, assim nao cadastrará o IP novamente
		$existe = 0;
		// O nome do arquivo que ficará guardado os IPs
		$arquivoVi = "visitas.txt";
		// Abrimos o arquivo posicionando o ponteiro ao final do arquivo
		$arquivo = fopen($arquivoVi,"a");
		// Inserimos cada linha do arquivo num array
		$file = file($arquivoVi);
		// Contamos quantos existem
		$total = count($file);
// _______________________________________
		// Realizamos o loop para comparar com cada linha do arquivo se o IP ja existe
		for ($i=0;$i<$total;$i++){	
		// Se existir a variavel $existe terá o valor 1				
			if ($file[$i] == "$ip
") $existe = 1;
			}
// _______________________________________
		// Caso $existe seja igual a 0 gravará o IP do visitante
			if ($existe == 0) fwrite ($arquivo,$ip . '
');
		fclose($arquivo);
		// Contamos quantos indices existem
		$this->num_visitas = count(file($arquivoVi));
	}
}
?>