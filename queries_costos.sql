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

SELECT DISTINCT(costo.id), AVG((costo.costo))
FROM usuario
inner join registro_horas on registro_horas.ingresado_por_id = usuario.id
inner join actividad on actividad.id = registro_horas.actividad_id
inner join area on area.id = actividad.area_id
inner join costo on costo.usuario_id = usuario.id
WHERE registro_horas.cliente_id = 1949 AND (costo.fecha_inicio >= '2017-08-01' AND costo.fecha_final <= '2018-01-31')
and area.id = 397
group by costo.id



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
SELECT a.nombre, a.id, GROUP_CONCAT(r.id) , GROUP_CONCAT((r.horas_invertidas))
FROM v1wq1ics1m037sn6.registro_horas r
JOIN actividad act on r.actividad_id = act.id
JOIN area a on a.id = act.area_id
JOIN proyecto_presupuesto proy ON proy.id = r.proyecto_presupuesto_id
WHERE proy.id = 6
AND r.horas_extraordinarias = 0
#order by a.nombre
GROUP BY a.id;

#POR PROYECTO Y POR AREA (PRESUPUESTO)
SELECT a.nombre, a.id,  SUM(r.horas_presupuestadas)
FROM v1wq1ics1m037sn6.registro_horas_presupuesto r
JOIN area a on a.id = r.area_id
JOIN proyecto_presupuesto proy ON proy.id = r.proyecto_id
WHERE proy.id = 6
group by a.id;

#COSTO POR PROYECTO Y AREA
SELECT area.id, area.nombre, AVG((costo.costo))
FROM usuario
inner join registro_horas on registro_horas.ingresado_por_id = usuario.id
inner join proyecto_presupuesto proy ON registro_horas.proyecto_presupuesto_id = proy.id
inner join actividad on actividad.id = registro_horas.actividad_id
inner join area on area.id = actividad.area_id
inner join costo on costo.usuario_id = usuario.id
where (costo.fecha_inicio < registro_horas.fecha_horas and costo.fecha_final > registro_horas.fecha_horas)
and proy.id = 6 
group by area.id;


SELECT id, actividad_id, cliente_id, ingresado_por_id, proyecto_presupuesto_id, fecha_horas, horas_invertidas, fecha_creacion, aprobado, horas_extraordinarias, creado_por_id, actualizado_por_id, fecha_actualizacion
FROM v1wq1ics1m037sn6.registro_horas;

#Registro horas dado un área y un proyecto
SELECT act.nombre, act.id, SUM(r.horas_invertidas), u.nombre, u.apellidos
FROM registro_horas r
INNER JOIN actividad act ON act.id = r.actividad_id
INNER JOIN area a on a.id = act.area_id
INNER JOIN proyecto_presupuesto p on p.id = r.proyecto_presupuesto_id
INNER JOIN usuario u ON u.id = r.ingresado_por_id
WHERE a.id = 396
AND proyecto_presupuesto_id = 6
group by act.id, u.id;

#costo por actividad usuario agrupada
SELECT actividad.id, actividad.nombre, AVG((costo.costo)), usuario.id, usuario.apellidos, (registro_horas.horas_invertidas)
SELECT  actividad.nombre, usuario.apellidos, cliente.razon_social, registro_horas.fecha_horas, (registro_horas.horas_invertidas),  registro_horas.ingresado_por_id, costo.costo
FROM usuario
inner join registro_horas on registro_horas.ingresado_por_id = usuario.id
inner join proyecto_presupuesto proy ON registro_horas.proyecto_presupuesto_id = proy.id
inner join actividad on actividad.id = registro_horas.actividad_id
inner join area on area.id = actividad.area_id
inner join costo on costo.usuario_id = usuario.id
inner join cliente on cliente.id = registro_horas.cliente_id
WHERE proy.id = 6
AND area.id = 397
AND (costo.fecha_inicio <= registro_horas.fecha_horas and costo.fecha_final >= registro_horas.fecha_horas)
#and actividad.id = 1142
GROUP By registro_horas.id
group by usuario.id, actividad.id

SELECT id, usuario_id, fecha_inicio, fecha_final, fecha_creacion, costo, fecha_actualizacion
FROM v1wq1ics1m037sn6.costo
WHERE costo.usuario_id = 40;



Select AVG(costo.costo), costo.fecha_inicio, costo.fecha_final from costo
where costo.usuario_id = 37
AND (
	month(costo.fecha_inicio) >= 
		(Select MONTH(MIN(registro_horas.fecha_horas)) 
		from registro_horas 
		where registro_horas.proyecto_presupuesto_id = 6 
		AND registro_horas.ingresado_por_id = 37) 
	AND
		YEAR(costo.fecha_inicio) >= (Select YEAR(MIN(registro_horas.fecha_horas)) 
		from registro_horas 
		where registro_horas.proyecto_presupuesto_id = 6 
		AND registro_horas.ingresado_por_id = 37)
		
	AND
	month(costo.fecha_final) <= 
		(Select MONTH(MAX(registro_horas.fecha_horas)) 
		from registro_horas 
		where registro_horas.proyecto_presupuesto_id = 6 
		AND registro_horas.ingresado_por_id = 37) 
	AND
		YEAR(costo.fecha_final) <= (Select YEAR(MAX(registro_horas.fecha_horas)) 
		from registro_horas 
		where registro_horas.proyecto_presupuesto_id = 6 
		AND registro_horas.ingresado_por_id = 37)
)
AND (costo.fecha_inicio >= '2017-08-01' AND costo.fecha_final <= '2018-01-31') 
GROUP BY costo.id

(Select MIN(registro_horas.fecha_horas) from registro_horas where registro_horas.proyecto_presupuesto_id = 6 AND registro_horas.ingresado_por_id = 37)

Select * 
from registro_horas 
where registro_horas.ingresado_por_id = 37
and registro_horas.proyecto_presupuesto_id = 6; 

#horas ingresadas por usuario
SELECT  usuario.id, usuario.nombre, usuario.apellidos, SUM((r.horas_invertidas))
FROM usuario
inner join registro_horas r on r.ingresado_por_id = usuario.id
WHERE r.proyecto_presupuesto_id = 6
AND r.horas_extraordinarias = 1
group by r.ingresado_por_id;

#horas presupuetadas por usuario
Select 
pr.usuario_id,
SUM(pr.horas_presupuestadas)
from registro_horas_presupuesto pr
where pr.proyecto_id = 6
group by pr.usuario_id


#query por usuario GENERAL
SELECT u.id, r.horas_invertidas, costo.costo, (r.horas_invertidas) * costo.costo, r.horas_extraordinarias
FROM usuario u
inner join registro_horas r on r.ingresado_por_id = u.id
inner join costo on costo.usuario_id = u.id
inner join cliente on cliente.id = r.cliente_id
where (r.fecha_horas >= '2017-11-01' and r.fecha_horas <= '2017-12-31')
AND (costo.fecha_inicio <= r.fecha_horas and costo.fecha_final >= r.fecha_horas)
AND u.id = 40
AND r.horas_extraordinarias = 0;

Select * from costo
where costo.usuario_id = 40;

#verify otras actividades
select r.id, r.fecha_horas, r.horas_invertidas, act.nombre ,r.ingresado_por_id, r.proyecto_presupuesto_id
from registro_horas r
inner join actividad act on act.id = r.actividad_id
inner join area a on a.id = act.area_id
where r.cliente_id = 1949
and a.id = 396;

