<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletim Informativo Diário - BID</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            color: #222;
        }

        /* ── Barra de navegação ── */
        nav {
            background: #1a3a5c;
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            box-shadow: 0 2px 6px rgba(0,0,0,.25);
        }

        nav .brand {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            padding: 14px 0;
            margin-right: 16px;
            letter-spacing: .5px;
        }

        nav a {
            color: #c8ddf0;
            text-decoration: none;
            padding: 14px 12px;
            font-size: .92rem;
            transition: background .2s, color .2s;
            border-radius: 4px 4px 0 0;
        }

        nav a:hover, nav a.active {
            background: #245a8a;
            color: #fff;
        }

        /* ── Container principal ── */
        main {
            max-width: 1100px;
            margin: 28px auto;
            padding: 0 16px;
        }

        /* ── Títulos de página ── */
        h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #1a3a5c;
            border-left: 4px solid #1a8a5a;
            padding-left: 12px;
        }

        /* ── Tabela ── */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.1);
        }

        th {
            background: #1a3a5c;
            color: #fff;
            padding: 11px 14px;
            text-align: left;
            font-size: .88rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        td {
            padding: 10px 14px;
            border-bottom: 1px solid #e8edf2;
            font-size: .92rem;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f5f9ff; }

        /* ── Botões ── */
        .btn {
            display: inline-block;
            padding: 7px 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: .88rem;
            font-weight: 600;
            text-decoration: none;
            transition: opacity .2s;
        }
        .btn:hover { opacity: .85; }
        .btn-primary  { background: #1a3a5c; color: #fff; }
        .btn-success  { background: #1a8a5a; color: #fff; }
        .btn-warning  { background: #d4860e; color: #fff; }
        .btn-danger   { background: #c0392b; color: #fff; }
        .btn-info     { background: #2471a3; color: #fff; }
        .btn-sm       { padding: 4px 10px; font-size: .82rem; }

        /* ── Formulários ── */
        .form-card {
            background: #fff;
            border-radius: 8px;
            padding: 28px;
            box-shadow: 0 1px 4px rgba(0,0,0,.1);
            max-width: 760px;
        }

        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-size: .88rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: .92rem;
            transition: border-color .2s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1a3a5c;
            box-shadow: 0 0 0 3px rgba(26,58,92,.12);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ── Alertas ── */
        .alert {
            padding: 11px 16px;
            border-radius: 5px;
            margin-bottom: 16px;
            font-size: .92rem;
            font-weight: 600;
        }
        .alert-success { background: #d4efdf; color: #1d6a3a; border-left: 4px solid #1a8a5a; }
        .alert-danger  { background: #fadbd8; color: #922b21; border-left: 4px solid #c0392b; }
        .alert-info    { background: #d6eaf8; color: #1a5276; border-left: 4px solid #2471a3; }

        /* ── Badge de status ── */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
        }
        .badge-ativo     { background: #d4efdf; color: #1d6a3a; }
        .badge-suspenso  { background: #fdebd0; color: #935116; }
        .badge-lesionado { background: #fadbd8; color: #922b21; }
        .badge-inativo   { background: #eaecee; color: #555; }
    </style>
</head>
<body>

<nav>
    <span class="brand">⚽ GestãoJogadores</span>
    <a href="index.php">Início</a>
    <a href="insere.php">Novo Jogador</a>
    <a href="relatorio.php">Relatório</a>
</nav>

<main>
