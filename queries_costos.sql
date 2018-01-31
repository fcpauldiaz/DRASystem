SELECT a.nombre, a.id, SUM(r.horas_invertidas)
FROM v1wq1ics1m037sn6.registro_horas r
JOIN actividad act on r.actividad_id = act.id
JOIN area a on a.id = act.area_id
WHERE r.cliente_id = 1949
GROUP BY a.id;

SELECT r.area_id, SUM(r.horas_presupuestadas)
FROM v1wq1ics1m037sn6.registro_horas_presupuesto r
WHERE r.cliente_id = 1949
GROUP BY r.area_id;

SELECT area.id, area.nombre, AVG((costo.costo))
FROM usuario
inner join registro_horas on registro_horas.ingresado_por_id = usuario.id
inner join actividad on actividad.id = registro_horas.actividad_id
inner join area on area.id = actividad.area_id
inner join costo on costo.usuario_id = usuario.id
WHERE registro_horas.cliente_id = 1949 AND (costo.fecha_inicio >= '2017-09-01' OR costo.fecha_final <= '2018-01-31')
and area.id = 396


Select * from costo
where costo.usuario_id = 40;

SELECT DISTINCT(usuario.id), usuario.nombre, usuario.apellidos, SUM(registro_horas.horas_invertidas)
FROM usuario
inner join registro_horas on registro_horas.ingresado_por_id = usuario.id
inner join actividad on actividad.id = registro_horas.actividad_id
inner join area on area.id = actividad.area_id
WHERE registro_horas.cliente_id = 1949
and area.id = 396
group by usuario.id;

SELECT SUM(DISTINCT(p.honorarios))
FROM v1wq1ics1m037sn6.registro_horas_presupuesto r
JOIN area a on a.id = r.area_id
JOIN proyecto_presupuesto p ON p.id = r.proyecto_id
WHERE r.cliente_id = 1949;

show status where `variable_name` = 'Threads_connected';

#POR PROYECTO Y AREA (INGRESADAS/INVERTIDAS)
SELECT a.nombre, a.id, SUM((r.horas_invertidas))
FROM v1wq1ics1m037sn6.registro_horas r
JOIN actividad act on r.actividad_id = act.id
JOIN area a on a.id = act.area_id
JOIN proyecto_presupuesto proy ON proy.id = r.proyecto_presupuesto_id
WHERE proy.id = 6
GROUP BY a.id;

#POR PROYECTO Y POR AREA (PRESUPUESTO)
SELECT a.nombre, a.id,  SUM(r.horas_presupuestadas)
FROM v1wq1ics1m037sn6.registro_horas_presupuesto r
JOIN area a on a.id = r.area_id
JOIN proyecto_presupuesto proy ON proy.id = r.proyecto_id
WHERE proy.id = 6
and a.id = 394;

#COSTO POR PROYECTO Y AREA
SELECT area.id, area.nombre, AVG((costo.costo))
FROM usuario
inner join registro_horas on registro_horas.ingresado_por_id = usuario.id
inner join proyecto_presupuesto proy ON registro_horas.proyecto_presupuesto_id = proy.id
inner join actividad on actividad.id = registro_horas.actividad_id
inner join area on area.id = actividad.area_id
inner join costo on costo.usuario_id = usuario.id
WHERE proy.id = 6 
and area.id = 394;

