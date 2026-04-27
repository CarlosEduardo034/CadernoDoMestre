<?php
session_start();
include("config/database.php");

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit;
}
?>

<h1>Lixeira</h1>
<ul id="listaLixeira"></ul>

<a href="Dashboard/Dashboard.php">Voltar</a>

<script>
function carregarLixeira() {    
    fetch("api/lixeira/listar.php")
    .then(res => res.json())
    .then(itens => {
        const lista = document.getElementById("listaLixeira");
        lista.innerHTML = "";

        itens.forEach(c => {
            const li = document.createElement("li");

            let infoTipo = "";

            if (c.tipo === "capitulo") {
                infoTipo = "📁 Capítulo";
            } else {
                infoTipo = `📄 Página (Capítulo: ${c.capitulo_nome || "Desconhecido"})`;
            }

            li.innerHTML = `
                <strong>${c.titulo}</strong><br>
                <small>${infoTipo}</small><br>

                <button onclick="restaurar(${c.id}, '${c.tipo}')">
                    Restaurar
                </button>

                <button onclick="excluirDefinitivo(${c.id}, '${c.tipo}')" style="color:red;">
                    Excluir definitivamente
                </button>
            `;

            lista.appendChild(li);
        });
    });
}

function restaurar(id, tipo) {
    const url = tipo === "capitulo"
        ? "api/capitulos/restaurar.php"
        : "api/paginas/restaurar.php";

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `id=${id}`
    })
    .then(res => res.text())
    .then(data => {
        if (data === "ok") carregarLixeira();
        else alert("Erro: " + data);
    });
}

function excluirDefinitivo(id, tipo) {
    if (!confirm("Excluir permanentemente?")) return;

    const url = tipo === "capitulo"
        ? "api/capitulos/deletar.php"
        : "api/paginas/deletar.php";

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `id=${id}`
    })
    .then(res => res.text())
    .then(data => {
        if (data === "ok") carregarLixeira();
        else alert("Erro: " + data);
    });
}

carregarLixeira();
</script>