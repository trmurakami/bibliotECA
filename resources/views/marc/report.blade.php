@extends('layouts.layout')

@section('title', 'BibliotECA - MARC Quality Analysis - Relatório')

@section('content')

<h4 class="mt-3">MARC Quality Analysis - Relatório</h4>

<div class="alert alert-warning" role="alert">

    <h3>Campos preenchidos ({{$tags['recordCount']}} registros)</h3>
    @php
    $jsonCampos = json_encode($tags['tagTotalCount']);
    @endphp
    <svg id="chart"></svg>

    <script src="https://d3js.org/d3.v6.min.js"></script>
    <script>
    // Dados
    var data = @json($tags['tagTotalCount']);

    // Configuração do gráfico
    var margin = {
        top: 20,
        right: 20,
        bottom: 30,
        left: 100
    };
    var width = 800;
    var height = 800;

    var svg = d3.select("#chart")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    // Ordenar os dados por tag
    data.sort(function(a, b) {
        return d3.descending(a.tag, b.tag);
    });

    // Escala para o eixo X
    var xScale = d3.scaleLinear()
        .domain([0, d3.max(data, function(d) {
            return d.total;
        })])
        .range([0, width]);

    // Escala para o eixo Y
    var yScale = d3.scaleBand()
        .domain(data.map(function(d) {
            return d.tag;
        }))
        .range([0, height])
        .padding(0.1);

    // Barras coloridas
    svg.selectAll(".bar")
        .data(data)
        .enter().append("rect")
        .attr("class", "bar")
        .attr("x", 0)
        .attr("y", function(d) {
            return yScale(d.tag);
        })
        .attr("width", function(d) {
            return xScale(d.total);
        })
        .attr("height", yScale.bandwidth())
        .attr("fill", function(d, i) {
            // Definir uma cor com base no índice da barra
            var colorScale = d3.scaleSequential()
                .domain([0, data.length - 1])
                .interpolator(d3.interpolateRainbow);
            return colorScale(i);
        });

    // Rótulos externos das barras
    svg.selectAll(".bar-label")
        .data(data)
        .enter().append("text")
        .attr("class", "bar-label")
        .attr("x", function(d) {
            return xScale(d.total) - 5;
        })
        .attr("y", function(d) {
            return yScale(d.tag) + yScale.bandwidth() / 2;
        })
        .attr("dy", "0.35em")
        .attr("text-anchor", "end")
        .attr("fill", "white")
        .text(function(d) {
            return d.total;
        });

    // Rótulos externos do eixo Y (tag)
    svg.selectAll(".y-label")
        .data(data)
        .enter().append("text")
        .attr("class", "y-label")
        .attr("x", -5)
        .attr("y", function(d) {
            return yScale(d.tag) + yScale.bandwidth() / 2;
        })
        .attr("dy", "0.35em")
        .attr("text-anchor", "end")
        .text(function(d) {
            return d.tag;
        });
    </script>

    <p>Foram encontrados os seguintes campos preenchidos:</p>
    <hr>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Tag</th>
                <th scope="col">Quantidade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags['tagCount'] as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection