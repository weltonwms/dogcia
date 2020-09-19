<input type="hidden" value="{{$vendasMensais}}" id="vendasMensais" />
<input type="hidden" value="{{$lucrosMensais}}" id="lucrosMensais" />

<div class="row">
    <div class="col-md-6">
      <div class="tile">
        <h3 class="tile-title"><i class="fa fa-cart-plus"></i> Vendas Mensais</h3>
        <div class="embed-responsive embed-responsive-16by9">
          <canvas class="embed-responsive-item" id="lineChartVendas"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="tile">
        <h3 class="tile-title"><i class="fa fa-usd"></i> Lucros Mensais</h3>
        <div class="embed-responsive embed-responsive-16by9">
          <canvas class="embed-responsive-item" id="lineChartLucros"></canvas>
        </div>
      </div>
    </div>
    


  </div>

  @push('scripts')
 
  <script src="{{ asset('template/js/plugins/chart.js') }}"></script>
  <script type="text/javascript">

function getDados(modelId){
    var model= JSON.parse( $(modelId).val() );
    var meses= ['',"Jan", "Fev", "Mar", "Abr", "Maio",'Jun', 'Jul', 'Ago', 'Set','Out','Nov','Dez'];
    var values= Object.values(model);
    var keys= Object.keys(model);
    var labels=[];
    keys.forEach(function(key){
      var numberMes= key.split('.')[0];
      labels.push( meses[numberMes] );
    });
    return {labels:labels,values:values};
    
  }

  function writeChart(labels, values, targetId){
    var data = {
        labels: labels,
        datasets: [
            {
                label: "First dataset",
                fillColor: "rgba(220,220,220,0.2)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: values
            }
        ]
        

    };
    var ctxl = $(targetId).get(0).getContext("2d");
    var lineChart = new Chart(ctxl).Line(data);

  }

  function toFloatBr(number){
   
    return parseFloat(number).toFixed(2);
  }


  (function start(){
    var dados= getDados("#vendasMensais");
    var dadosLucro= getDados("#lucrosMensais");
    
    writeChart(dados.labels,dados.values.map(toFloatBr), "#lineChartVendas");
    writeChart(dadosLucro.labels,dadosLucro.values.map(toFloatBr), "#lineChartLucros");
   
  })();

  

    
  </script> 
  @endpush