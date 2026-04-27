<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit;
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <body>
        <main>
            <h2>Esse é o seu caderno</h2>

            <input type="text" id="nome" placeholder="Nome do capítulo">
            <input type="text" id="descricao" placeholder="Descrição">
            <button onclick="criarCapitulo()">Criar Capítulo</button>

            <h2>Seus capítulos</h2>
            <ul id="listaCapitulos"></ul>

            <button onclick="listarLixeira()">Ver Lixeira</button>
        </main>

    <script>
        function criarCapitulo() {
            const nome = document.getElementById("nome").value;
            const descricao = document.getElementById("descricao").value;

            fetch("../api/capitulos/criar.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `nome=${encodeURIComponent(nome)}&descricao=${encodeURIComponent(descricao)}`
            })
            .then(res => res.text())
            .then(data => {
                if (data === "ok") {
                    carregarCapitulos();
                } else {
                    alert("Erro: " + data);
                }
            });
        }

        function carregarCapitulos() {
            fetch("../api/capitulos/listar.php")
            .then(res => res.json())
            .then(capitulos => {
                const lista = document.getElementById("listaCapitulos");
                lista.innerHTML = "";

                capitulos.forEach(c => {
                    const li = document.createElement("li");

                    li.innerHTML = `
                        <div id="view-${c.id}">
                            <a href="../views/capitulo.php?id=${c.id}">
                                <strong>${c.nome}</strong>
                            </a>
                            - ${c.descricao || ""}

                            <button onclick="editarCapitulo(${c.id}, '${c.nome}', '${c.descricao || ""}')">
                                Editar
                            </button>
                            <button onclick="excluirCapitulo(${c.id})" style="color:red;">
                                Excluir
                            </button>
                        </div>

                        <div id="edit-${c.id}" style="display:none;">
                            <input type="text" id="nome-${c.id}" value="${c.nome}">
                            <input type="text" id="desc-${c.id}" value="${c.descricao || ""}">

                            <button onclick="salvarCapitulo(${c.id})">Salvar</button>
                        </div>
                    `;
                    lista.appendChild(li);
                });
            });
        }

        carregarCapitulos();

        function editarCapitulo(id, nome, descricao) {
            document.getElementById(`view-${id}`).style.display = "none";
            document.getElementById(`edit-${id}`).style.display = "block";
        }

        function salvarCapitulo(id) {
            const nome = document.getElementById(`nome-${id}`).value;
            const descricao = document.getElementById(`desc-${id}`).value;
 
            fetch("../api/capitulos/editar.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${id}&nome=${encodeURIComponent(nome)}&descricao=${encodeURIComponent(descricao)}`
            })
            .then(res => res.text())
            .then(data => {
                if (data === "ok") {
                    carregarCapitulos(); // recarrega lista
                } else {
                    alert("Erro: " + data);
                }
            });
        }

        function excluirCapitulo(id) {
            const confirmar = confirm("Tem certeza que deseja excluir este capítulo?");

            if (!confirmar) return;

            fetch("../api/capitulos/excluir.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${id}`
            })
            .then(res => res.text())
            .then(data => {
                if (data === "ok") {
                    carregarCapitulos();
                } else {
                    alert("Erro: " + data);
                }
            });
        }

        function listarLixeira() {
            window.location.href = "../views/lixeira.php";
        }
    </script>
</body>
</html>