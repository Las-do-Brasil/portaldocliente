<?php

namespace App\Http\Controllers\Winthor;

use Auth;
use App\Models\Order;
use Illuminate\Http\Request;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class OrderWinthorController extends Controller
{
    /**
     * get by CNPJ from win Thor API
     * @param int $client_code
     * @param string $produtcs_id
     * @return object
     */
    public function sendOrder($order, $cart)
    {
        // instacia controller que faz api calls
        $priceWinthor = new PriceWinthorController();

        $user = Auth::guard('web')->user();

        $url = config('winthor.winthor_url').'api/Pcpedc';
       // $token = config('winthor.token');


        $itensFormatted = $this->getItensFormatted($cart, $user );


    $dados = array(
        "numped"=> 0,
        "data"=>  date('Y-m-d').'T'.date('H:i:s'),
        "vltotal"=> 0,
        "codcli"=> $user->codcli,
        "codusur"=> $user->codusur,
        "dtentrega"=> date('Y-m-d').'T'.date('H:i:s'),
        "vltabela"=> 0, //enviar zerado - calculado pela integradora
        "codfilial"=> "1",
        "vldesconto"=> 0,
        "tipovenda"=> "VP",
        "obs"=> null,
        "vlcustoreal"=> 0,
        "vlcustofin"=> 0,
        "vlfrete"=> $order->shipping_cost,
        "vloutrasdesp"=> 0,
        "totpeso"=> 0.0006, // peso total do pedido
        "totvolume"=> 0, // volume total dos itens
        "codpraca"=> 31,
        "numitens"=> 1,  //Calcular quantidade de itens distintos (linhas pcpedi)
        "codemitente"=> 1,
        "dtcancel"=> null,
        "posicao"=> "P",
        "vlatend"=> 117936,
        "operacao"=> "N",
        "numcar"=> 0,
        "codcob"=> "DEP",
        "hora"=> date('H'),
        "minuto"=> date('i'),
        "numseqentrega"=> null,
        "custoentrega"=> null,
        "codsupervisor"=> 1,
        "campanha"=> null,
        "numpedcli"=> $order->order_number,
        "condvenda"=> 1,
        "percvenda"=> 100,
        "obs1"=> "NAO ACEITA ENTREGA PARCIAL",
        "obs2"=> null,
        "perdesc"=> 0,
        "negociado"=> null,
        "codplpag"=> 165,  //Codigo interno fixo // fazer de para de acordo com os tipos de pagamento - vai enviar tabela para o Bruno
        "codfunccancel"=> null,
        "numtransvenda"=> null,
        "montando"=> null,
        "numpedrca"=> '999'.rand(11111,99999), // enviar número aleatório
        "fretedespacho"=> "C",
        "freteredespacho"=> null,
        "codfornecfrete"=> null,
        "tipocarga"=> null, //enviar null
        "prazo1"=> 1,
        "prazo2"=> null,
        "prazo3"=> null,
        "prazo4"=> null,
        "prazo5"=> null,
        "prazo6"=> null,
        "prazo7"=> null,
        "prazo8"=> null,
        "prazo9"=> null,
        "prazo10"=> null,
        "prazo11"=> null,
        "prazo12"=> null,
        "prazomedio"=> 1,
        "obsentrega1"=> "",
        "obsentrega2"=> "",
        "obsentrega3"=> null,
        "codepto"=> 0,
        "tipoembalagem"=> null, //enviar null
        "dtlibera"=> null,
        "codmotorista"=> null,
        "codfilialnf"=> null,
        "dtnftransf"=> null,
        "numnftransf"=> null,
        "numcupom"=> null,
        "serieecf"=> null,
        "codmotbloqueio"=> 0,
        "codmotcancel"=> null,
        "tipooper"=> null,
        "coddistrib"=> "LAS",
        "numviasmapasep"=> null,
        "numvolume"=> null,
        "numseqentr"=> null,
        "codfunccx"=> null,
        "numcaixa"=> null,
        "numnota"=> 0,
        "vlcustorep"=> null, //enviar null
        "vlcustocont"=> null, //enviar null
        "vldescneg"=> null,
        "percdesccanal"=> null,
        "percdescabc"=> null,
        "percdesccli"=> null,
        "percdescneg"=> null,
        "numnotamanif"=> 0,
        "seriemanif"=> null,
        "origemped"=> "W", // W: WEB
        "codfornecvdireto"=> null,
        "codfuncexparqol"=> null,
        "dataexparqol"=> null,
        "numseqmontagem"=> null,
        "exportado"=> "N",
        "especiemanif"=> null,
        "eanentrega"=> 0,
        "eancobranca"=> 0,
        "codfuncconf"=> null,
        "codcontrato"=> 0,
        "numpedentfut"=> null,
        "dtfat"=> null,
        "codfunclibera"=> null,
        "numviaspedido"=> 0,
        "horafat"=> null,
        "minutofat"=> null,
        "numpedtv3"=> null,
        "numpedorigem"=> null,
        "codfuncembalador"=> null,
        "numnotaconsig"=> null,
        "pedidoembalado"=> null,
        "numserieequip"=> null,
        "numorca"=> 0,
        "numcarmanif"=> 0,
        "codusur2"=> null,
        "codusur3"=> null,
        "horainicialsep"=> null,
        "minutoinicialsep"=> null,
        "horafinalsep"=> null,
        "minutofinalsep"=> null,
        "dtwms"=> null,
        "dtvenc1"=> null,
        "dtvenc2"=> null,
        "dtvenc3"=> null,
        "numempenho"=> null,
        "numprocesso"=> null,
        "numfonterecurso"=> null,
        "codfunccalcfrete"=> null,
        "dtcalcfrete"=> null,
        "vlfretenf"=> null,
        "prazoadicional"=> null,
        "horalibera"=> null,
        "minutolibera"=> null,
        "codcliconsignacao"=> null,
        "baixaestcli"=> null,
        "codsupervisor2"=> null,
        "codsupervisor3"=> null,
        "numtranswms"=> null,
        "tipocfoptv4"=> "OS",
        "prazoponderado"=> "N",
        "dtemissaomapa"=> null,
        "horaemissaomapa"=> null,
        "minutoemissaomapa"=> null,
        "dataprotocolo"=> null,
        "horaprotocolo"=> null,
        "minutoprotocolo"=> null,
        "codfuncprotocolo"=> null,
        "numseqcorreio"=> null,
        "numcontratocorreio"=> null,
        "vlbonific"=> 0,
        "numordemcarga"=> null,
        "obsfretenf1"=> null,
        "obsfretenf2"=> null,
        "obsfretenf3"=> null,
        "aliqicmsfretenf"=> null,
        "baseicmsfretenf"=> null,
        "vlicmsfretenf"=> null,
        "dtinicialcheckout"=> null,
        "dtfinalcheckout"=> null,
        "codfuncemissaomapa"=> null,
        "dtvalidade"=> null,
        "codclinf"=> null,
        "numpedvale"=> null,
        "datapedcli"=> "1899-12-30T00:00:00", // data de processamento do pedido no e-commerce
        "dtaberturapedpalm"=> null,
        "dtfechamentopedpalm"=> null,
        "numcaraux"=> null,
        "numnotatransfdep"=> null,
        "codcondicaovenda"=> null,
        "totvldescfin"=> null,
        "totvldesccom"=> null,
        "totvlbonific"=> null,
        "totvldescflex"=> null,
        "totvlredcomiss"=> null,
        "importacupom"=> null,
        "dtagendaentrega"=> "1899-12-30T00:00:00", // enviar esse fixo
        "eancomprador"=> null,
        "eanlocalentrega"=> null,
        "numpedecf"=> null,
        "numpedbnf"=> null,
        "totvlredcomisssup"=> null,
        "conferindopedido"=> null,
        "codatendimento"=> null,
        "tributaporregiaofilial"=> null,
        "restricaotransp"=> "N",
        "numseqrota"=> null,
        "importado"=> "N",
        "dtexportado"=> null,
        "dtimportado"=> null,
        "numcarfab"=> null,
        "numtabela"=> null,
        "codestabelecimento"=> null,
        "broker"=> "N",
        "prontaentrega"=> null,
        "motivoposicao"=> null,
        "numcaixafiscal"=> null,
        "conciliaimportacao"=> "N",
        "pagtoantecipado"=> null,
        "perdescfin"=> 0,
        "codclirecebedor"=> null,
        "numregiao"=> 1,
        "codprofissional"=> null,
        "numorcafilial"=> null,
        "tipoformularionf"=> null,
        "numpedweb"=> null,
        "reservaritenstv7"=> null,
        "numprevenda"=> null,
        "codveiculo"=> null,
        "numecf"=> null,
        "geracp"=> "N",
        "importadoservprinc"=> null,
        "dtimportacaoservprinc"=> null,
        "dtexportacaoservint"=> null,
        "exportadoservint"=> null,
        "nomearquivofv"=> null,
        "perccomprofissional"=> null,
        "dtinicialsep"=> null,
        "dtfinalsep"=> null,
        "tiporetirada"=> null,
        "geonumlicitacao"=> null,
        "numorcaprinc"=> null,
        "codendent"=> null,
        "usacfopvendanatv10"=> "S",
        "gerardadosnfpaulista"=> "N",
        "codmotivo"=> 0,
        "bloqueioedicao"=> null,
        "dtexportacao"=> null,
        "numseqenvio"=> null,
        "numseqretorno1"=> null,
        "numseqretorno2"=> null,
        "usaintegracaowms"=> "N",
        "codfuncexpintwms"=> null,
        "codfuncimpintwms"=> null,
        "dtimportacao"=> null,
        "codfornecredespacho"=> null,
        "numloteintwms"=> null,
        "restricaotransporte"=> null,
        "dtchegadacliente"=> null,
        "vendaassistida"=> null,
        "dtinicialpend"=> null,
        "dtfinalpend"=> null,
        "totpesoliq"=> null,
        "vendaassistiva"=> null,
        "versaorotina"=> null,
        "pedduplicado"=> null,
        "tipoprioridadeentrega"=> "B",
        "totpesoliqagrupado"=> null,
        "totpesoagrupado"=> null,
        "totvolumeagrupado"=> null,
        "numpedorigemfrete"=> null,
        "localizacaopedido"=> null,
        "subserie"=> null,
        "percfrete"=> null,
        "brinde"=> "N",
        "usacredrca"=> "N",
        "usadebcredrca"=> "N",
        "bonificaltdebcredrca"=> "N",
        "trocaaltdebcredrca"=> "N",
        "brokeraltdebcredrca"=> "N",
        "crmaltdebcredrca"=> "N",
        "tipomovccrca"=> "VV",
        "numvolumeagrupado"=> null,
        "especievolume"=> null,
        "marcavolume"=> null,
        "dtconfgarantia"=> null,
        "codfuncconfgarantia"=> null,
        "numpedtv1"=> null,
        "recarga"=> null,
        "codautorizacaotef"=> null,
        "nsutef"=> null,
        "codadmcartao"=> null,
        "codtransp"=> null,
        "codigosazonalidade"=> null,
        "rotina"=> null,
        "codusur4"=> null,
        "codpracadestino"=> null,
        "gerarcontroledeentrega"=> null,
        "numcaranterior"=> null,
        "usasaldocontacorrentedescfin"=> null,
        "bloqueiosaldoccdescfin"=> null,
        "codfuncsep"=> null,
        "numpeso"=> null,
        "protocolonfp"=> null,
        "codmotivo2"=> null,
        "bloqfinanceiro"=> null,
        "bloqcomercial"=> null,
        "vlentrada"=> null,
        "numviasetiqueta"=> null,
        "vendatriangular"=> "N",
        "codvisita"=> 0,
        "integradora"=> null,
        "valordescfin"=> 0,
        "tipodocumento"=> "A",
        "vlfreteretido"=> null,
        "vlstfreteretido"=> null,
        "percfreteretido"=> null,
        "percstfreteretido"=> null,
        "prazomedioplpag"=> null,
        "dtlibera2"=> null,
        "codfunclibera2"=> null,
        "ufdesembaraco"=> null,
        "localdesembaraco"=> null,
        "custobonificacao"=> null,
        "codfornecbonific"=> null,
        "codbnf"=> null,
        "obsentrega4"=> null,
        "percsaldoreservarca"=> null,
        "placaveiculo"=> null,
        "softnumlanc"=> null,
        "cfopbnfdegusta"=> null,
        "turnoentrega"=> null,
        "vendaexportacao"=> "N",
        "pedidoavaria"=> null,
        "idagrupamento"=> null,
        "numlista"=> null,
        "devsimbolica"=> null,
        "codautorizacaotefweb"=> null,
        "vldescabatimento"=> 0,
        "dtiniciodigitacaopedido"=> date('Y-m-d').'T'.date('H:i:s'), // data do pedido do e-commerce
        "dtfimdigitacaopedido"=> date('Y-m-d').'T'.date('H:i:s'),// data do pedido do e-commerce
        "tipofv"=> null,
        "motoristaveiculo"=> null,
        "ufveiculo"=> null,
        "altaposmapasep"=> "S",
        "codplpagetico"=> null,
        "codplpaggenerico"=> null,
        "codclitv8"=> null,
        "fornecentrega"=> "N",
        "rotinalanc"=> "E-COMMERCE v1",
        "rotinalancultalt"=> "E-COMMERCE v1",
        "reservaestoquetv7"=> null,
        "dtexportacaowms"=> null,
        "dtimportacaowms"=> null,
        "codfunclibdesc"=> null,
        "perdesclib"=> null,
        "dtlimitefat"=> null,
        "numtransacaotransf"=> null,
        "numccf"=> null,
        "dataempenho"=> null,
        "codunidadeexecutora"=> null,
        "contaordem"=> "N",
        "log"=> null,
        "log1"=> null,
        "log2"=> null,
        "log3"=> null,
        "log4"=> null,
        "codpostagem"=> null,
        "agrupamento"=> "N",
        "numtransvendatv13"=> null,
        "perdescavista"=> null,
        "plpagavista"=> null,
        "numpedvanxml"=> null,
        "tipoemissao"=> null,
        "contingenciatv14"=> null,
        "chavenfe"=> null,
        "numpedtv14"=> null,
        "codendentcli"=> null,
        "numpedagrupado"=> null,
        "numviaspedagrupado"=> null,
        "enviadocompra"=> null,
        "enviadovenda"=> null,
        "justificativacontigencia"=> null,
        "dtahoraentradacontigencia"=> null,
        "vltributos"=> null,
        "cartaodotz"=> null,
        "horaemissao"=> null,
        "utilizavendaporembalagem"=> "N",
        "consumiunumnfe"=> null,
        "vldescontocupom"=> null,
        "nsusociotorcedor"=> null,
        "sistemalegado"=> null,
        "codpromocaomed"=> null,
        "vldescsociotorcedor"=> null,
        "idtipopresenca"=> null,
        "vlsubtotal"=> null,
        "numregexp"=> null,
        "numchaveexp"=> null,
        "numdrawback"=> null,
        "codcontato"=> null,
        "liberaRetaguarda"=> null,
        "codfuncliberouret"=> null,
        "dataliberouret"=> null,
        "codmoedaestrangeira"=> null,
        "vlrmoedaestrangeira"=> null,
        "taxacasomoedareal"=> 1,
        "ambientenfce"=> null,
        "versaofaturamento"=> null,
        "docemissao"=> null,
        "qrcodenfce"=> null,
        "chavenfce"=> null,
        "numformulario"=> null,
        "numselo"=> null,
        "codfrete"=> null,
        "colunafrete"=> null,
        "codmoedaestrangeiraoriginal"=> null,
        "latitude"=> null,
        "longitude"=> null,
        "precisaolatlng"=> null,
        "pedidopagoecommerce"=> null,
        "numcirurgia"=> null,
        "numfechamentomovcx"=> null,
        "dtmovimentocx"=> null,
        "codbancocm"=> null,
        "codedital"=> null,
        "codcomrca"=> null,
        "codredecli"=> null,
        "codacordoparceria"=> null,
        "emitircupomfiscal"=> null,
        "vendanfseried"=> null,
        "numseriesat"=> null,
        "datahoraemissaosat"=> null,
        "chavesat"=> null,
        "qrcodesat"=> null,
        "numsessaosat"=> null,
        "codsefazsat"=> null,
        "codretornosat"=> null,
        "protocolonfce"=> null,
        "coletafrete"=> "N",
        "vlfretetransp"=> null,
        "vlfreteempentrega"=> null,
        "coddisp"=> null,
        "meldataemissaoped"=> null,
        "vildtseparacomplfim"=> null,
        "vildtseparacomplini"=> null,
        "obsnfce"=> null,
        "vltotalcomtroco"=> null,
        "vendalocestrang"=> "N",
        "numcoleta"=> null,
        "transportadora"=> null,
        "cgcfrete"=> null,
        "iefrete"=> null,
        "uffrete"=> null,
        "situacaosat"=> null,
        "codstatussat"=> null,
        "envioumsg"=> null,
        "usacredrcatipobnf"=> null,
        "uidregistro"=> null,
        "idparceiro"=> null,
        "assinatura"=> null,
        "dataexpedicao"=> null,
        "dataretorno"=> null,
        "datasaida"=> null,
        "multiplicadorduplic"=> null,
        "copiaidenticapeddup"=> "N",
        "exportacrm"=> null,
        "tribvendatriangular"=> null,
        "numos"=> null,
        "vloperacaofrete"=> null,
        "vlbasestfrete"=> null,
        "vlicmsstfrete"=> null,
        "percentualstfrete"=> null,
        "pagchequemoradia"=> "N",
        "qtparcelas"=> null,
        "nsu"=> null,
        "codautorizacao"=> null,
        "tipooperacaotef"=> null,
        "codbandeiratef"=> null,
        "vltxent"=> null,
        "condfinanc"=> null,
        "planosuppli"=> null,
        "numprecar"=> null,
        "comissaoemitente"=> "N",
        "codbrex"=> null,
        "vlfreteoutrasdesp"=> null,
        "statuspedidociashop"=> null,
        "derrubadacarga"=> null,
        "programada"=> null,
        "lasStatus9832"=> null,
        "mensagem"=> null,
        "ordemsep"=> null,
        "ordemconf"=> null,
        "pagamentoaprovadociashop"=> null,
        "codplpagant"=> null,
        "recalprecoaltplpag"=> null,
        "tipocontacorrente"=> "R",
        "impexp"=> null,
        "materialdeconstrucao"=> null,
        "vendalocalcliex"=> "N",
        "codgerente"=> 0,
        "vlverbacmv"=> 0,
        "vlverbacmvcli"=> 0,
        "numpedmktplace"=> null,
        "dataefetivaentregaciashop"=> null,
        "situacaoprocessamento"=> "N",
        "tipocalcvlatend"=> null,
        "codfuncliberadtentrega"=> null,
        "usacorteciashop"=> null,
        "indicadordesconto"=> null,
        "indicadoracrescimo"=> null,
        "idremessaweb"=> null,
        "numviasmapasepri"=> null,
        "permiteprodsemdistribuicao"=> null,
        "ultimasituacaocfat"=> null,
        "dataultimasituacaocfat"=> null,
        "numtransentcrossdock"=> null,
        "numtransentorigconsig"=> null,
        "numtransentorigtv10"=> null,
        "vljurosparcelamento"=> null,
        "qtpalete"=> null,
        "totpesopalete"=> null,
        "bloquearfaturamento"=> null,
        "dtprevfatHistorico"=> date('Y-m-d').'T'.date('H:i:s'), // data do pedido
        "tipobloqueio"=> null,
        "msgbloqueioenviada"=> null,
        "datahoraprocessamento"=> null,
        "codsup"=> 0,
        "numpedhube"=> null,
        "dtnsu"=> null,
        "processamentotriggersup"=> null,
        "numficha"=> null,
        "descintermediador"=> null,
        "cnpjintermediador"=> null,
        "opervendaexpindireta"=> null,
        "xblq"=> null,
        "pcPedis"=> $itensFormatted['itens']

        );


        // consulta api
        $prodsPrice = $priceWinthor->getProductPriceByClientCod($user->codcli, $itensFormatted['ids']);


        // converte resultado de object para array par interagir no foreach
        $prodsPrice = (array) $prodsPrice;

        // adiciona valores que veio da api "$prodsPrice['produtos']" no object de prods que será exibido na tela
        foreach ($dados['pcPedis'] as $key => $row){


            if (isset($prodsPrice['produtos'][$key])) {
                $row2 = $prodsPrice['produtos'][$key] ;

                if ($row['codprod'] == $row2->codprod) {

                    $dados['pcPedis'][$key]['ptabela']              = $row2->valorbrl;
                    $dados['pcPedis'][$key]['qtunitcx']             = $row2->qtunitcx;
                    $dados['pcPedis'][$key]['pvendabase']           = $row2->valorbrl;
                    $dados['pcPedis'][$key]['pdolar']               = $row2->valorusd;
                    $dados['pcPedis'][$key]['ptabelafabricazfm']    = $row2->pcompra;
                    $dados['pcPedis'][$key]['pcompra']              = $row2->pcompra;
                    $dados['pcPedis'][$key]['unidade']              = $row2->unidade;
                    $dados['pcPedis'][$key]['codauxiliar']              = $row2->codprod;

                }
            }

        }


        // auth Winthor
        $authWinthor = new AuthWinthorController();
        $token = $authWinthor->Authenticate();

        $response = Http::withToken($token)->post($url, $dados );



        // echo '<pre>';


        //     // print_r($response->throw());
        //     // print_r($response->throw()->json());

        //     print_r( $response->status());
        //     print_r( $response->object());

        // echo '</pre>';
        // die();



     return $response;

    }



    /**
     * getItensFormatted
     *
     * @param  mixed $cart
     * @param  mixed $cod_user
     * @return array
     */
    private function getItensFormatted($cart, $user){


        $ids_itens = array();
        $itens_result = array();
        $result = array();
        $seq = 1;


        foreach ($cart->items as $itemCart) {


            $price = @$itemCart['item']['price'];

            if (empty($price)) {
                $price = 1;
            }


            $itemFormatted = array(

                "numped"=> 0,
                "data"=> date('Y-m-d').'T'.date('H:i:s'),
                "codcli"=> $user->codcli,
                "codprod"=> $itemCart['item']['sku'],
                "codusur"=> $user->codusur,
                "qt"=> 63,
                "pvenda"=> $price,
                "ptabela"=> 1631.28, // pegar api  // TODO
                "numcar"=> 0, // mandar zerado
                "posicao"=> "P",
                "st"=> 0,
                "vlcustofin"=> 0, //enviar zerado
                "vlcustoreal"=> 0, //enviar zerado
                "percom"=> 0,
                "perdesc"=> 0,
                "qtfalta"=> 0,
                "numseq"=> $seq, // enviar incremental para cada item adicionado
                "tipopeso"=> null,
                "percomtab"=> null,
                "perdesctab"=> null,
                "codmotnaocompra"=> null,
                "vldesccustocmv"=> 0,
                "qtseparada"=> null,
                "qtvendaemb"=> null,
                "pvendaemb"=> null,
                "vloutros"=> 0,
                "qtembalagem"=> null,
                "pvendaembalagem"=> null,
                "codauxiliar"=> 999999,
                "vlcustorep"=> null,  //enviar null
                "vlcustocont"=> null, //enviar null
                "codcertific"=> null,
                "pvendabase"=> $price, //entregue pela api // TODO
                "nomeconcorrente"=> null,
                "preco"=> null,
                "prazo"=> null,
                "qtnaocompra"=> 0,
                "codfilialretira"=> "1",
                "numtira"=> null,
                "codfuncsep"=> null,
                "vldescsuframa"=> 0,
                "numlote"=> null,
                "vldescrepasse"=> null,
                "refcor"=> null,
                "codfuncconf"=> null,
                "dataconf"=> null,
                "vldescicmisencao"=> 0,
                "qtoriginal"=> null,
                "vldescfornec"=> null,
                "vlfrete"=> null,
                "vlipi"=> 0,
                "qtorig"=> null,
                "qtsepararun"=> null,
                "qtsepararcx"=> null,
                "codst"=> null, //enviar null
                "vldescfin"=> 0,
                "percipi"=> 0,
                "iva"=> 0,
                "aliqicms1"=> 0,
                "aliqicms2"=> 0,
                "pauta"=> 0,
                "percbasered"=> 0,
                "vldesccom"=> 0,
                "perdesccom"=> 0,
                "perdescfin"=> null,
                "vlbonific"=> 0,
                "perbonific"=> 0,
                "poriginal"=> $price, //entregue pela api
                "vlrebaixacmv"=> null,
                "numaplic"=> null,
                "perfretecmv"=> 0,
                "vldescrodape"=> null,
                "stclientegnre"=> 0,
                "imprime"=> null,
                "complemento"=> null,
                "custofinest"=> 1, // pegar api
                "percbaseredstfonte"=> 0,
                "percbaseredst"=> 0,
                "perdesccusto"=> 0,
                "codicmtab"=> null, //enviar null
                "txvenda"=> null, //enviar null
                 "percom2"=> 0,
                "percom3"=> 0,
                "perciss"=> 0,
                "vliss"=> 0,
                "numtranswms"=> null,
                "codpromocao"=> null,
                "prazomedio"=> null,
                "localizacao"=> null,
                "vlrepasse"=> 0,
                "pbonific"=> 0,
                "percvenda"=> null,
                "vldescpissuframa"=> 0,
                "coddegustacao"=> null,
                "qtlocalizada"=> null,
                "perdescflex"=> null,
                "vldescflex"=> null,
                "perredcomiss"=> null,
                "vlredcomiss"=> null,
                "tipodescaplicado"=> null,
                "pbaserca"=> $price, //entregue pela api
                "pesobruto"=> null,
                "numverbarebcmv"=> 0,
                "condvenda"=> null,
                "codplpag"=> null,
                "eancodprod"=> 0,
                "brinde"=> "N",
                "percomsup"=> null,
                "perredcomisssup"=> null,
                "vlredcomisssup"=> null,
                "baseicst"=> 0,
                "numop"=> null,
                "qtcx"=> 0,
                "qtpecas"=> 0,
                "codecf"=> null,
                "letracomiss"=> null,
                "vlacrescrodape"=> null,
                "numconferencia"=> null,
                "perdescisentoicms"=> 0,
                "percomprof"=> null,
                "numcaraux"=> null,
                "pvenda1"=> $price, //entregue pela api
                "percagregadorst"=> null,
                "vlverbacmvcli"=> 0,
                "vloutrasdesp"=> null,
                "exportadoservint"=> null,
                "dtexportacaoservint"=> null,
                "dtimportacaoservprinc"=> null,
                "importadoservprinc"=> null,
                "codvasilhame"=> null,
                "qtapanha"=> null,
                "qtunitcx"=> 1, //entregue pela api // TODO
                "truncaritem"=> "N",
                "abastecido"=> null,
                "qtimediata"=> null,
                "codfunclanc"=> null,
                "rotinalanc"=> null,
                "dtlanc"=> null,
                "codfuncultalter"=> null,
                "rotinaultlalter"=> null,
                "dtultlalter"=> date('Y-m-d').'T'.date('H:i:s'), // data atual
                "codfuncalteracaoos"=> null,
                "dtalteracaoos"=> null,
                "qtpendos"=> null,
                "vldifaliquotas"=> 0,
                "basedifaliquotas"=> 0,
                "percdifaliquotas"=> 0,
                "geragnreCnpjcliente"=> null,
                "proddescricaocontrato"=> null,
                "numos"=> null,
                "dtinicialsep"=> null,
                "dtfinalsep"=> null,
                "dataconffim"=> null,
                "situacaoos"=> null,
                "numviasos"=> null,
                "dtinicialpend"=> null,
                "dtfinalpend"=> null,
                "codfuncpend"=> null,
                "dtlibos"=> null,
                "codfunclibos"=> null,
                "localizacaoos"=> null,
                "numosorigem"=> null,
                "perdescpolitica"=> 0,
                "pvendaanterior"=> 0,
                "tipoentrega"=> "RP",
                "tvbonif"=> null,
                "codigobrinde"=> null,
                "codfuncajusteos"=> null,
                "dtajusteos"=> null,
                "politicaprioritaria"=> "N",
                "tipocalculost"=> null,
                "qtunitemb"=> null,
                "vlfreteRateio"=> null,
                "vloutrasRateio"=> null,
                "baseicstAntRateio"=> null,
                "stAntRateio"=> null,
                "stDifRateio"=> null,
                "vlverbacmv"=> 0,
                "codfuncconf2"=> null,
                "tiposeparacao"=> null,
                "numvolumesconferencia"=> null,
                "rotina"=> null,
                "percdescpis"=> 0,
                "percdesccofins"=> 0,
                "vldescreducaopis"=> 0,
                "vldescreducaocofins"=> 0,
                "percom4"=> 0,
                "numetiqueta"=> null,
                "dtgeracaoos"=> null,
                "codfuncaltlote"=> null,
                "precofvbruto"=> null,
                "alternativo"=> null,
                "siglaqualidade"=> null,
                "volumedesejado"=> null,
                "codbase"=> null,
                "codformula"=> null,
                "usadebcredrcabrind"=> null,
                "coddesconto"=> null,
                "codcombo"=> null,
                "dtentrega"=> null,
                "movimentacontacorrenterca"=> null,
                "idpatrimonio"=> null,
                "vlredpvendasimplesna"=> null,
                "vlredcmvsimplesnac"=> null,
                "perdescfob"=> null,
                "sugestao"=> null,
                "codemitenteitempedido"=> null,
                "rpImediata"=> null,
                "grupofaturamento"=> null,
                "participagiro"=> null,
                "qtun"=> null,
                "vlipioutras"=> null,
                "percipioutras"=> null,
                "vldescabatimento"=> 0,
                "percdescabatimento"=> 0,
                "qtreservant"=> null,
                "vldescboleto"=> null,
                "numseqitemcontrato"=> null,
                "numlista"=> null,
                "idvenda"=> null,
                "percdescindustria"=> null,
                "perdescboleto"=> null,
                "codlinhaprazo"=> null,
                "stpbaserca"=> 0,
                "stptabela"=> 0,
                "qtlitragem"=> 0,
                "bonific"=> "N",
                "precomaxconsum"=> 0,
                "descprecofab"=> null, // verificar
                "rotinalancultalt"=> null,
                "numcaixa"=> null,
                "percicm"=> null,
                "codcontrato"=> null,
                "proddescricaodanfe"=> null,
                "rotinalancbrinde"=> null,
                "perdescinicomiss"=> null,
                "perdescfimcomiss"=> null,
                "concedermaiorcomissreg"=> null,
                "vlsubtotitem"=> null,
                "perdescnegociado"=> null,
                "formanegociacao"=> null,
                "perdescavista"=> null,
                "negociacaoposterior"=> null,
                "codprecofixo"=> null,
                "vlacresfretekg"=> null,
                "statussucata"=> null,
                "ptabelaautpecas"=> null,
                "grpregrabrinde"=> null,
                "numitemped"=> 0,
                "vlitemtributos"=> null,
                "perctributos"=> null,
                "totalizadoraliquota"=> null,
                "perdescpauta"=> 0,
                "origemst"=> null,
                "vldescsociotorcedor"=> null,
                "codpromocaomed"=> null,
                "iniciointervalodescquant"=> null,
                "altdescpromocmed"=> null,
                "qtfaltadigitacao"=> null,
                "numrecopi"=> null,
                "numerorecopi"=> null,
                "unidade"=> "UN", // pegar via api
                "ambiente"=> null,
                "taxacasomoedareal"=> 1,
                "codmoedaestrageira"=> null,
                "vlrmoedaestrageira"=> null,
                "qtdiasentregaitem"=> 0,
                "percipiecf"=> null,
                "vlipiecf"=> null,
                "baseipiecf"=> null,
                "usaunidademaster"=> "N",
                "dtiniciopromolote"=> null,
                "dtfimpromolote"=> null,
                "numpedmaster"=> null,
                "qtSeparadamanif"=> null,
                "lotecontrato"=> null,
                "codcontrolevasilhame"=> null,
                "codvasilhameecf"=> null,
                "qtsaidavasilhame"=> null,
                "qtvendidavasilhame"=> null,
                "vlacrescvasilhame"=> null,
                "pvendavasilhame"=> null,
                "margemmin"=> null,
                "percredaliqipi"=> 0,
                "pdolar"=> 0, // pegar via api // TODO
                "corte"=> "N",
                "codindicemultiplicador"=> null,
                "pvendaliq"=> null,
                "numseriesat"=> null,
                "coddescontobaserca"=> null,
                "codprodcesta"=> 0,
                "numseqcestabasica"=> 0,
                "origemdepreco"=> null,
                "vltotservico"=> null,
                "produzirTinta"=> null,
                "vlfcppart"=> 0,
                "vlicmspart"=> 0,
                "percprovpart"=> 100, // verificar
                "vlicmsdifaliqpart"=> null, // enviar null
                "percbaseredpart"=>  null, // enviar null
                "vlicmspartdest"=>null, // enviar null
                "vlbasepartdest"=>null, // enviar null
                "aliqfcp"=> null, // enviar null
                "aliqinternadest"=> null, // enviar null
                "vlicmspartrem"=> 0,
                "numpedras"=> null,
                "numseqras"=> null,
                "vlbasepartDesativado"=> null,
                "aliqfcppartDesativado"=> null,
                "aliqicmspartDesativado"=> null,
                "vlacrespartDesativado"=> null,
                "aliqinterorigpart"=> null, // enviar null
                "vlbasepart"=> null,
                "aliqfcppart"=> null,
                "aliqicmspart"=> null,
                "vlacrespart"=> null,
                "vlipiptabela"=> null, // enviar null
                "vlipipbaserca"=>null, // enviar null
                "vlicmspartptabela"=> null, // enviar null
                "vlicmspartpbaserca"=>null, // enviar null
                "codcest"=> null,
                "qtdifpeso"=> null,
                "origmerctrib"=> null,
                "codfigvendatriangular"=> 0,
                "coddescontosimulador"=> 0,
                "numosservico"=> null,
                "codbarrabalanca"=> null,
                "codfiscal"=> null, // enviar null
                "sittribut"=> null, // enviar null
                "versaoservicopartilha"=> null,
                "prodimportadopeps"=> null,
                "numtransentpeps"=> null,
                "ptabelafabricazfm"=> 176,  // pegar api //TODO
                "codsupervisor"=> null,
                "qtminatacvenda"=> null,
                "tipodescatacvenda"=> null,
                "qtdiasentrega"=> null,
                "pcompra"=> 176,  // pegar api  //TODO
                "dtpreventrega"=> "1899-12-30T00:00:00",  // enviar essa data fixa
                "vldesccarcaca"=> null,
                "devolucaocarcaca"=> "N",
                "fatconvcombo"=> null,
                "numchaveexp"=> null,
                "numdrawback"=> null,
                "numregexp"=> null,
                "tipocombo"=> null,
                "codoferta"=> null,
                "cnpjfabricante"=> null,
                "fabricante"=> null,
                "indescalarelevante"=> "S",
                "codagregacao"=> null,
                "codbeneficiofiscal"=> null,
                "vlbasefcpicms"=> null,
                "vlbasefcpst"=> 0,
                "vlbcfcpstret"=> null,
                "perfcpstret"=> null,
                "vlfcpstret"=> null,
                "perfcpsn"=> null,
                "vlcredfcpicmssn"=> null,
                "vlfecp"=> 0,
                "vlacrescimofuncep"=> null,
                "peracrescimofuncep"=> null,
                "aliqicmsfecp"=> 0,
                "pglp"=> null,
                "pgnn"=> null,
                "pgni"=> null,
                "vpart"=> null,
                "brindevarejo"=> null,
                "utilizoumotorcalculo"=> "N",
                "anp"=> null,
                "descanp"=> null,
                "programada"=> null,
                "lasStatus9832"=> null,
                "numseqitembrinde"=> null,
                "reclassificado"=> null,
                "qtorigtv8"=> null,
                "cupomdesconto"=> null,
                "codcupomdesconto"=> null,
                "codmaquina"=> null,
                "baixaqtfrenteloja"=> null,
                "dtconsolidacaoauxpro"=> null,
                "idremessaweb"=> null,
                "numverbacampanha"=> null,
                "perccustfornec"=> null,
                "vlcustfornec"=> null,
                "codprecocesta"=> null,
                "posicaocfat"=> null,
                "regimeespisenstfonte"=> null,
                "observacaostfonte"=> null,
                "codconfigfuncepmed"=> null,
                "coddeposito"=> null,
                "codformulacmv"=> null,
                "pcompraorig"=> null,
                "codfaixapromocao"=> null,
                "fatorgramaturalicit"=> null,
                "numpedcli"=> null,
                "numlotepromocaomed"=> null,
                "codigointegracaowms"=> null,
                "numviasmapasepri"=> null,
                "logprogram"=> null,
                "logultimobloqueio"=> null,
                "numempenho"=> null,
                "codedital"=> null,
                "precosemimposto"=> null,
                "logaltdiasitem"=> null


            );
            $seq++;

            array_push($itens_result, $itemFormatted);

            if (!empty($itemCart['item']['sku'])) {
                array_push($ids_itens, $itemCart['item']['sku']);
            }


        }

        $result['itens']  = $itens_result;
        $result['ids']  = $ids_itens;

        return $result;
    }
}