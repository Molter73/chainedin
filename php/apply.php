<?php
/**
 * Permite interactuar con la tabla de aplicantes a una posición basado en
 * el método HTTP utilizado.
 *
 * POST: Permite a un candidato presentarse para una posición. Parámetros:
 * - user_id
 * - job_id
 *
 * GET: Devuleve listas de trabajos-aplicantes. Parámetros:
 * - user_id: filtra resultados para incluir sólo trabajos de este usuario.
 * - job_id: filtra resultados para incluir sólo postulantes de este trabajo.
 * - count: cantidad de resultados a devolver (default: 20)
 * - page: Página de resultados a obtener (default:0)
 */
?>
