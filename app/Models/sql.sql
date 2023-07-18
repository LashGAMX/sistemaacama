-- SQLBook: Code
/* Vista lista intermediario*/
CREATE VIEW ViewIntermediarios as SELECT
	inter.Id_intermediario,
	inter.Id_cliente,
	cli.Nombres,
    cli.A_paterno,
    cli.A_materno,
    cli.RFC,
    cli.Id_tipo_cliente,
    inter.Laboratorio as Id_laboratorio,
    suc.Sucursal,
    inter.Correo,
    inter.Direccion,
    inter.Tel_oficina,ViewLoteDetalleGA
    inter.Extension,
    inter.Celular1,
    inter.Detalle,
    inter.created_at,
    inter.updated_at,
    cli.deleted_at
FROM intermediarios as inter
INNER JOIN clientes as cli
ON inter.Id_cliente = cli.Id_cliente
INNER JOIN sucursales as suc
ON inter.Laboratorio = suc.Id_sucursal
--nueva vista intermediarios con id_user_c y m
CREATE VIEW ViewIntermediarios as SELECT
	inter.Id_intermediario,
	inter.Id_cliente,
	cli.Nombres,
    cli.A_paterno,
    cli.A_materno,
    cli.RFC,
    cli.Id_tipo_cliente,
    cli.Id_user_c,
    cli.Id_user_m,
    inter.Laboratorio as Id_laboratorio,
    suc.Sucursal,
    inter.Correo,
    inter.Direccion,
    inter.Tel_oficina,
    inter.Extension,
    inter.Celular1,
    inter.Detalle,
    inter.Id_usuario,
    inter.created_at,
    inter.updated_at,
    cli.deleted_at
FROM intermediarios as inter
INNER JOIN clientes as cli
ON inter.Id_cliente = cli.Id_cliente
INNER JOIN sucursales as suc
ON inter.Laboratorio = suc.Id_sucursal

/* Vista Lista clientes generales*/

CREATE VIEW ViewGenerales as SELECT
	gen.Id_cliente_general,gen.Id_cliente,cli.Id_user_c,
    cli.Id_user_m,cli.created_at,cli.updated_at,cli.deleted_at,gen.Empresa,gen.Alias,
    gen.Id_intermediario,cli2.Nombres,cli2.A_paterno,cli2.A_materno,cli2.RFC
FROM clientes_general as gen
INNER JOIN clientes as cli
ON gen.Id_cliente = cli.Id_cliente
INNER JOIN intermediarios as inter
ON gen.Id_intermediario = inter.Id_intermediario
INNER JOIN clientes as cli2
ON inter.Id_cliente = cli2.Id_cliente

/* Vista Lista parametros */
CREATE VIEW ViewParametros as SELECT param.Id_parametro,param.Id_laboratorio,lab.Sucursal,param.Id_tipo_formula,tipo.Tipo_formula,param.Id_area,area.Area_analisis ,param.Id_user_c,param.Id_user_m,param.Id_rama,ram.Rama,param.Parametro,
param.Id_unidad,uni.Unidad,uni.Descripcion,param.Id_metodo,param.Limite,param.Id_tecnica,tec.Tecnica,param.Id_procedimiento,pro.Procedimiento,param.Id_matriz,mat.Matriz,param.Id_simbologia,param.Envase,param.Curva,
sim.Simbologia,inf.Simbologia as Simbologia_inf, inf.Id_simbologia_info,inf.Descripcion as Descripcion2,sim.Descripcion as Descripcion_sim,met.Metodo_prueba,met.Clave_metodo,param.Precio,param.F_inicio_vigencia,param.F_fin_vigencia,param.created_at,param.updated_at,
param.deleted_at FROM parametros as param
INNER JOIN sucursales as lab
ON param.Id_laboratorio = lab.Id_sucursal
INNER JOIN tipo_formulas as tipo
ON param.Id_tipo_formula = tipo.Id_tipo_formula
INNER JOIN ramas as ram
ON param.Id_rama = ram.Id_rama
INNER JOIN unidades as uni
ON param.Id_unidad = uni.Id_unidad
INNER JOIN metodo_prueba as met
ON param.Id_metodo = met.Id_metodo
INNER JOIN procedimiento_analisis as pro
ON param.Id_procedimiento = pro.Id_procedimiento
INNER JOIN matriz_parametros as mat
ON param.Id_matriz = mat.Id_matriz_parametro
INNER JOIN simbologia_parametros as sim
ON param.Id_simbologia = sim.Id_simbologia
INNER JOIN area_analisis as area
ON param.Id_area = area.Id_area_analisis
INNER JOIN simbologia_informe as inf
ON param.Id_simbologia_info = inf.Id_simbologia_info
INNER JOIN tecnicas as tec
ON param.Id_tecnica = tec.Id_tecnica
/* Vista Lista norma-parametros */
CREATE VIEW ViewNormaParametro as SELECT n.Id_norma_param,n.Id_norma,nor.Norma,nor.Clave,n.Id_parametro,p.Parametro,p.Id_matriz,mat.Matriz,p.Id_simbologia,sim.Simbologia,sim.Descripcion,n.Reporte 
FROM norma_parametros as n
INNER JOIN sub_normas as nor
ON n.Id_norma = nor.Id_subnorma
INNER JOIN parametros as p
ON n.Id_parametro = p.Id_parametro
INNER JOIN matriz_parametros as mat
ON p.Id_matriz = mat.Id_matriz_parametro
INNER JOIN simbologia_parametros as sim
ON p.Id_simbologia = sim.Id_simbologia

/* Vista Lista View_limite001*/
CREATE VIEW ViewLimite001 as SELECT lim.*,tipo.Detalle,cla.Tipo_cuerpo FROM limitepnorma_001 as lim 
INNER JOIN detalle_tipoCuerpos as tipo 
ON lim.Id_categoria = tipo.Id_detalle 
INNER JOIN parametros as pa 
ON lim.Id_parametro = pa.Id_parametro
INNER JOIN clasificaciones as cla
ON lim.Id_tipo = cla.Id_clasificacion

/*Vista   Lista precio catalgo*/
CREATE VIEW ViewPrecioCat as SELECT cat.*, pa.Id_laboratorio,pa.Sucursal,pa.Id_tipo_formula,pa.Tipo_formula,pa.Id_area,pa.Area_analisis,
pa.Id_rama,pa.Rama,pa.Parametro,pa.Id_unidad,pa.Unidad,pa.Descripcion,pa.Id_metodo,pa.Limite,pa.Id_tecnica,
pa.Tecnica,pa.Id_procedimiento,pa.Procedimiento,pa.Id_matriz,pa.Matriz,pa.Id_simbologia,pa.Envase,pa.Simbologia,pa.Simbologia_inf,
pa.Descripcion_sim,pa.Metodo_prueba,pa.Clave_metodo
FROM `precio_catalogo` as cat
INNER JOIN ViewParametros as pa
ON pa.Id_parametro = cat.Id_parametro

/* Lista precio paquete */
CREATE VIEW ViewPrecioPaq as SELECT
p.Id_precio,p.Id_paquete,sub.Id_norma,sub.Norma, sub.Id_subnorma, sub.Clave,p.Precio,p.Id_tipo,p.created_at,p.updated_at,p.deleted_at
FROM precio_paquete as p
INNER JOIN sub_normas as sub
ON p.Id_paquete = sub.Id_subnorma;

/* Lista detalle Intermediario */
CREATE VIEW ViewDetalleInter as SELECT 
detalle.Id_detalle,inter.Id_intermediario,inter.Id_cliente,inter.Id_laboratorio,inter.Sucursal, inter.Nombres,inter.A_paterno,inter.RFC,inter.Detalle,detalle.Id_nivel,nivel.Nivel,
nivel.Descuento as DescNivel,detalle.Descuento,detalle.created_at,detalle.updated_at,detalle.deleted_at 
FROM detalle_intermediarios as detalle 
INNER JOIN ViewIntermediarios as inter ON detalle.Id_intermediario = inter.Id_cliente 
INNER JOIN nivel_clientes as nivel ON detalle.Id_nivel = nivel.Id_nivel

/* Lista precio intermediario catalogo */
CREATE VIEW ViewPrecioCatInter as SELECT 
pre.Id_precio,pre.Id_intermediario,pre.Tipo_precio,pre.Id_catalogo,cat.Parametro,pre.Precio,pre.Original,pre.Descuento,pre.created_at,pre.updated_at,pre.deleted_at
FROM precio_intermediario as pre
INNER JOIN ViewPrecioCat as cat
ON pre.Id_catalogo = cat.Id_parametro

/* Lista precio intermediario Paquete */
CREATE VIEW ViewPrecioPaqInter as SELECT pre.Id_precio,pre.Id_intermediario,pre.Tipo_precio,pre.Id_catalogo,sub.Id_norma,sub.Norma,sub.Clave,pre.Precio,pre.Original,pre.Descuento,pre.created_at,pre.updated_at,pre.deleted_at FROM precio_intermediario as pre
INNER JOIN sub_normas as sub
ON pre.Id_catalogo = sub.Id_subnorma

/* Lista cotizacion */
CREATE VIEW ViewCotizacion as SELECT 
cot.Id_cotizacion,cot.Id_direccion,cot.Id_general,cot.Id_intermedio,inter.Nombres as NomInter,inter.A_paterno as ApeInter,cot.Id_cliente,cot.Id_sucursal,cot.Nombre,cot.Direccion,
cot.Atencion,cot.Telefono,cot.Correo,cot.Tipo_servicio,ser.Servicio,cot.Tipo_descarga,des.Descarga,
cot.Id_norma,nor.Norma,nor.Clave_norma,cot.Id_subnorma,cot.Fecha_muestreo,cot.Frecuencia_muestreo,cot.Tomas,
cot.Tipo_muestra as Id_tipoMuestra,tipo.Tipo,cot.Promedio as Id_promedio,prom.Promedio,cot.Numero_puntos,cot.Tipo_reporte,
cot.Tiempo_entrega,cot.Observacion_interna,cot.Observacion_cotizacion,cot.Folio_servicio,
cot.Folio,cot.Fecha_cotizacion,cot.Metodo_pago,cot.Precio_analisis,cot.Descuento,cot.Precio_analisisCon,cot.Iva,cot.Precio_muestreo,cot.Precio_catalogo,cot.Sub_total,
cot.Costo_total,cot.Estado_cotizacion,est.Estado,est.Descripcion as Descripcion_estado,
cot.Supervicion,cot.Creado_por,usr.name as NameC,cot.Actualizado_por,usr2.name as NameA,cot.created_at,cot.updated_at,cot.deleted_at
FROM cotizacion as cot
INNER JOIN normas as nor
ON cot.Id_norma = nor.Id_norma
INNER JOIN ViewIntermediarios as inter
ON cot.Id_intermedio = inter.Id_intermediario
INNER JOIN tipo_servicios as ser
ON cot.Tipo_servicio = ser.Id_tipo
INNER JOIN tipo_descargas as des
ON cot.Tipo_descarga = des.Id_tipo
INNER JOIN cotizacion_estado as est
ON cot.Estado_cotizacion = est.Id_estado
INNER JOIN users as usr
ON cot.Creado_por = usr.id
INNER JOIN users as usr2
ON cot.Actualizado_por = usr2.id
INNER JOIN tipo_muestraCot as tipo
ON cot.Tipo_muestra = tipo.Id_muestraCot
INNER JOIN promedioCot as prom
ON cot.Promedio = prom.Id_promedioCot

/* Lista cotizacion parametros */
CREATE VIEW ViewCotParam as SELECT 
param.Id_parametro,param.Id_cotizacion,param.Id_subnorma,param.Extra,param.created_at,param.updated_at,
param.deleted_at,
p.Parametro,p.Matriz,p.Simbologia,p.Metodo_prueba,p.Clave_metodo,p.Limite,p.Unidad,p.Tipo_formula,p.Id_tipo_formula ,param.Reporte
FROM cotizacion_parametros as param
INNER JOIN ViewParametros as p
ON param.Id_subnorma = p.Id_parametro

/* Lista solicitudes */
CREATE VIEW ViewSolicitud as SELECT 
sol.*,inter.Nombres,inter.A_paterno,gen.Empresa,suc.Empresa as Empresa_suc,suc.Estado,dir.Direccion,
con.Nombres as Nom_con,con.A_paterno as Nom_pat,con.Telefono as Tel_con,ser.Servicio,ser.Descripcion,
des.Descarga,nor.Norma,nor.Clave_norma,sub.Norma as Nor_sub,sub.Clave
FROM solicitudes  as sol
INNER JOIN ViewIntermediarios as inter
ON inter.Id_intermediario = sol.Id_intermediario
INNER JOIN ViewGenerales as gen
ON gen.Id_cliente = sol.Id_cliente
INNER JOIN sucursales_cliente as suc
ON suc.Id_sucursal = sol.Id_sucursal
INNER JOIN direccion_reporte as dir
ON dir.Id_direccion = sol.Id_direccion
INNER JOIN contactos_cliente as con
ON con.Id_contacto = sol.Id_contacto
INNER JOIN tipo_servicios as ser
ON sol.Id_servicio = ser.Id_tipo
INNER JOIN tipo_descargas as des
ON des.Id_tipo = sol.Id_descarga
INNER JOIN normas as nor
ON nor.Id_norma = sol.Id_norma
INNER JOIN sub_normas as sub
ON sub.Id_subnorma = sol.Id_subnorma  



CREATE VIEW ViewSolicitud2 as SELECT 
sol.*,inter.Nombres,inter.A_paterno,gen.Empresa,suc.Empresa as Empresa_suc,suc.Estado,ser.Servicio,ser.Descripcion,
des.Descarga,nor.Norma,nor.Clave_norma,sub.Norma as Nor_sub,sub.Clave
FROM solicitudes  as sol
INNER JOIN ViewIntermediarios as inter
ON inter.Id_intermediario = sol.Id_intermediario
INNER JOIN ViewGenerales as gen
ON gen.Id_cliente = sol.Id_cliente
INNER JOIN sucursales_cliente as suc
ON suc.Id_sucursal = sol.Id_sucursal
INNER JOIN tipo_servicios as ser
ON sol.Id_servicio = ser.Id_tipo
INNER JOIN tipo_descargas as des
ON des.Id_tipo = sol.Id_descarga
INNER JOIN normas as nor
ON nor.Id_norma = sol.Id_norma
INNER JOIN sub_normas as sub
ON sub.Id_subnorma = sol.Id_subnorma



/* Lista Solicitud parametros */
CREATE VIEW ViewSolicitudParametros as SELECT sol.Id_parametro as Id_solParam,sol.Id_solicitud,sol.Extra,pa.Id_parametro,pa.Parametro,pa.Id_area,pa.Area_analisis,pa.Id_tipo_formula,sol.Asignado,s.Folio_servicio,pa.Metodo_prueba,pa.Unidad,sol.Reporte FROM solicitud_parametros as sol
INNER JOIN ViewParametros as pa
ON sol.Id_subnorma = pa.Id_parametro
INNER JOIN solicitudes as s
ON sol.Id_solicitud = s.Id_solicitud

/* Lista Puntos Solicitud Generales */
CREATE VIEW ViewPuntoGenSol as SELECT sol.*,puntos.Id_sucursal,puntos.Punto_muestreo FROM solicitud_puntos as sol
INNER JOIN puntos_muestreogen as puntos
ON sol.Id_muestreo = puntos.Id_punto

/*  Lista Localidades*/
CREATE VIEW ViewLocalidades as SELECT loc.Id_localidad,loc.Id_estado,es.Nombre as estado,loc.Nombre,loc.created_at,loc.updated_at,loc.deleted_at FROM localidades as loc
INNER JOIN estados as es
ON loc.Id_estado = es.Id_estado
/* Lista ViewDireccionSir */
CREATE VIEW ViewDireccionSir as SELECT cl.*, es.Nombre as NomEstado,loc.Nombre as NomMunicipio FROM clientes_siralab as cl
INNER JOIN estados as es
ON cl.Estado = es.Id_estado
INNER JOIN localidades as loc
ON cl.Municipio = loc.Id_localidad

/* Lista ViewPuntoMuestreoSir*/
CREATE VIEW ViewPuntoMuestreoSir as SELECT p.*,ti.Titulo,t.Cuerpo,d.Detalle as Agua FROM puntos_muestreo as p
INNER JOIN titulo_concesion_sir as ti
ON p.Titulo_consecion = ti.Id_titulo
INNER JOIN tipo_cuerpo as t
ON p.Cuerpo_receptor = t.Id_tipo
INNER JOIN detalle_tipoCuerpos as d
ON p.Uso_agua = d.Id_detalle

/* Lista ViewPuntoMuestreoSolSir */
CREATE VIEW ViewPuntoMuestreoSolSir as SELECT pu.Id_punto as Id_puntoSol,pu.Id_solPadre,pu.Id_solicitud,pu.Id_muestreo,sir.Id_punto,sir.Id_sucursal,con.Id_titulo,con.Titulo,sir.Punto,sir.Anexo,sir.Siralab,sir.Pozos,sir.Cuerpo_receptor,sir.Uso_agua 
FROM solicitud_puntos as pu
INNER JOIN puntos_muestreo as sir
ON pu.Id_muestreo = sir.Id_punto
INNER JOIN titulo_concesion_sir as con
ON sir.Titulo_consecion = con.Id_titulo
/* Lista ViewSolicitudGenerada */
CREATE VIEW ViewSolicitudGenerada as SELECT sol.*,gen.Id_solicitudGen,gen.Captura,gen.Id_muestreador,us.name,gen.Estado as StdSol, gen.Punto_muestreo, gen.Id_user_c as IdUserC, gen.Id_user_m as IdUserM FROM solicitudes_generadas as gen
INNER JOIN ViewSolicitud2 as sol
ON gen.Id_solicitud = sol.Id_solicitud
INNER JOIN users as us
ON gen.Id_muestreador = us.id
            /* Modificación de vista en prueba */
CREATE VIEW ViewSolicitudGenerada as SELECT sol.*,gen.Id_solicitudGen,gen.Captura,gen.Id_muestreador,us.name,gen.Estado as StdSol, gen.Punto_muestreo, gen.Id_user_c as IdUserC, gen.Id_user_m as IdUserM FROM solicitudes_generadas as gen
INNER JOIN ViewSolicitud as sol
ON gen.Id_solicitud = sol.Id_solicitud
INNER JOIN users as us
ON gen.Id_muestreador = us.id
/* Campo generales */ 
CREATE VIEW ViewCampoGenerales as SELECT c.Id_general,c.Id_solicitud,c.Captura,c.Id_equipo,c.Id_equipo2,t.Id_muestreador,t.Equipo,t.Marca,t.Modelo,t.Serie,t2.Equipo as Equipo2,t2.Marca as Marca2,t2.Modelo as Modelo2, t2.Serie as Serie2 ,c.Temperatura_a,c.Temperatura_b,c.Latitud,c.Longitud,c.Altitud,c.Pendiente,c.Criterio,c.Supervisor,c.created_at,c.updated_at,c.deleted_at 
FROM campo_generales as c
INNER JOIN termometro_campo as t
ON c.Id_equipo = t.Id_termometro
INNER JOIN termometro_campo as t2
ON c.Id_equipo2 = t2.Id_termometro

/* Lista ViewObservacionMuestra */
CREATE VIEW ViewObservacionMuestra as  SELECT obs.Id_observacion,obs.Id_analisis,obs.Id_area,obs.Id_tipo,obs.Ph,obs.Solido,obs.Olor,obs.Color,obs.Observaciones,pro.Folio,pro.Descarga,pro.Cliente,pro.Empresa,
pro.Ingreso,pro.Proceso,pro.Reporte,pro.ClienteG,pro.Hora_entrada,sol.Clave_norma,sol.created_at FROM observacion_muestra as obs
INNER JOIN proceso_analisis as pro
ON obs.Id_analisis = pro.Id_solicitud
INNER JOIN ViewSolicitud as sol
ON obs.Id_analisis = sol.Id_solicitud

/* Liseta ViewTipoFormula  */ 
CREATE VIEW ViewTipoFormula as SELECT t.Id_tipo_formula,t.Tipo_formula,t.Concentracion,t.Id_area,a.Area_analisis,t.Id_user_c,t.Id_user_m,t.created_at,t.updated_at,t.deleted_at FROM tipo_formulas as t 
INNER JOIN area_analisis as a
ON t.Id_area = a.Id_area_analisis

/* Lista ViewLoteAnalisis */ 
CREATE VIEW ViewLoteAnalisis as SELECT lo.*,a.Area_analisis,pa.Parametro,pa.Id_tipo_formula,pa.Tipo_formula,pa.Limite FROM lote_analisis as  lo 
INNER JOIN area_analisis as a
ON lo.Id_area = a.Id_area_analisis
INNER JOIN ViewParametros as pa
ON lo.Id_tecnica = pa.Id_parametro


/* Lista ViewDetalleLote */ 
CREATE VIEW ViewLoteDetalle as SELECT lote.*,sol.Folio_servicio,sol.Empresa,sol.Empresa_suc,pa.Parametro,pa.Limite,pa.Tecnica,pa.Tipo_formula, ar.Area_analisis, control.Control FROM lote_detalle as lote
INNER JOIN ViewSolicitud2 as sol
ON lote.Id_analisis = sol.Id_solicitud
INNER JOIN ViewParametros as pa
ON lote.Id_parametro = pa.Id_parametro
INNER JOIN control_calidad as control
ON lote.Id_control = control.Id_control
INNER JOIN area_analisis as ar
ON pa.Id_area = ar.Id_area_analisis

/* Lista ViewTipoFormulaAreas */
CREATE VIEW ViewTipoFormulaAreas as SELECT tipo.Id_tVipo,tipo.Id_formula,form.Tipo_formula,form.Concentracion,tipo.Id_area,areas.Area_analisis,tipo.created_at,tipo.updated_at,tipo.deleted_at FROM tipo_formula_areas as tipo
INNER JOIN tipo_formulas as form
ON tipo.Id_formula = form.Id_tipo_formula
INNER JOIN area_analisis as areas
ON tipo.Id_area = areas.Id_area_analisis

/* Lista  ViewLoteDetalleEspectro */
CREATE VIEW ViewLoteDetalleEspectro AS SELECT det.*,sol.Folio_servicio,sol.Num_tomas,sol.Clave_norma,param.Parametro,param.Limite,con.Control,cod.Codigo,cod.Num_muestra FROM lote_detalle_espectro as det
INNER JOIN  lote_analisis as lot
ON det.Id_lote = lot.Id_lote
INNER JOIN  ViewSolicitud2 as sol
ON det.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON det.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as con
ON det.Id_control = con.Id_controlViewLoteDetalleSolidos
INNER JOIN codigo_parametro as cod
ON det.Id_codigo = cod.Id_codigo

/* Lista ViewLoteDetalleGA */
CREATE VIEW ViewLoteDetalleGA as SELECT det.*,sol.Folio_servicio,sol.Num_tomas,sol.Clave_norma,param.Parametro,param.Limite,con.Control,cod.Codigo,cod.Num_muestra FROM lote_detalle_ga as det
INNER JOIN  lote_analisis as lot
ON det.Id_lote = lot.Id_lote
INNER JOIN  ViewSolicitud2 as sol
ON det.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON det.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as con
ON det.Id_control = con.Id_control
INNER JOIN codigo_parametro as cod
ON det.Id_codigo = cod.Id_codigo

/* Lista ViewLoteDetalleHH */ 
CREATE VIEW ViewLoteDetalleHH as SELECT det.*,sol.Folio_servicio,sol.Clave_norma,sol.Empresa_suc,param.Parametro,param.Limite,control.Control,cod.Codigo,cod.Num_muestra FROM lote_detalle_hh as det
INNER JOIN  lote_analisis as lot
ON det.Id_lote = lot.Id_lote
INNER JOIN  ViewSolicitud as sol
ON det.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON det.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as control
ON det.Id_control = control.Id_control
INNER JOIN codigo_parametro as cod
ON det.Id_codigo = cod.Id_codigo

/* Lista ViewLoteDetalleSolidos */ 

CREATE VIEW ViewLoteDetalleSolidos as SELECT det.*,sol.Empresa_suc,sol.Clave_norma,sol.Folio_servicio,param.Parametro,param.Limite,control.Control,control.Descripcion,cod.Codigo,cod.Num_muestra FROM lote_detalle_solidos as det
INNER JOIN ViewSolicitud2 as sol
ON det.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON det.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as control
ON det.Id_control = control.Id_control
INNER JOIN codigo_parametro as cod
ON det.Id_codigo = cod.Id_codigo

/* Lista ViewLoteDetalleColiformes */ 

CREATE VIEW ViewLoteDetalleColiformes as SELECT col.*,sol.Empresa_suc,sol.Clave_norma,sol.Folio_servicio,param.Parametro,control.Control,control.Descripcion,cod.Codigo,cod.Num_muestra FROM lote_detalle_coliformes as col
INNER JOIN ViewSolicitud2 as sol
ON col.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON col.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as control
ON col.Id_control = control.Id_control
INNER JOIN codigo_parametro as cod
ON col.Id_codigo = cod.Id_codigo

/* Lista ViewLoteDetalleEcoli */ 

CREATE VIEW ViewLoteDetalleEcoli as SELECT col.*,sol.Empresa_suc,sol.Clave_norma,sol.Folio_servicio,param.Parametro,control.Control,control.Descripcion,cod.Codigo,cod.Num_muestra FROM lote_detalle_Ecoli as col
INNER JOIN ViewSolicitud2 as sol
ON col.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON col.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as control
ON col.Id_control = control.Id_control
INNER JOIN codigo_parametro as cod
ON col.Id_codigo = cod.Id_codigo

/* Lista ViewLoteDetalleEnterococos */
CREATE VIEW ViewLoteDetalleEnterococos as SELECT col.*,sol.Empresa_suc,sol.Clave_norma,sol.Folio_servicio,param.Parametro,param.Limite,control.Control,control.Descripcion,cod.Codigo,cod.Num_muestra FROM lote_detalle_enterococos as col
INNER JOIN ViewSolicitud2 as sol
ON col.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON col.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as control
ON col.Id_control = control.Id_control
INNER JOIN codigo_parametro as cod
ON col.Id_codigo = cod.Id_codigo;
/* Lista ViewLoteDetalleDbo */ 

CREATE VIEW ViewLoteDetalleDbo as SELECT col.*,sol.Empresa_suc,sol.Clave_norma,sol.Folio_servicio,param.Parametro,param.Limite,control.Control,control.Descripcion,cod.Codigo,cod.Num_muestra FROM lote_detalle_dbo as col
INNER JOIN ViewSolicitud2 as sol
ON col.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON col.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as control
ON col.Id_control = control.Id_control
INNER JOIN codigo_parametro as cod
ON col.Id_codigo = cod.Id_codigo

/* Lista ViewEnvases */ 

CREATE VIEW ViewEnvases as SELECT en.*,uni.Unidad FROM envase as en
INNER JOIN unidades as uni
ON en.Id_unidad = uni.Id_unidad

/* Lista ViewEnvaseParametro */ 

CREATE VIEW ViewEnvaseParametro as SELECT env.*,lab.Area,lab.Reportes,lab.deleted_at as stdArea ,pa.Parametro,pa.Rama,pa.Tipo_formula,en.Nombre,en.Volumen,lab.Id_responsable,pre.Preservacion, uni.Unidad,lab.Id_area FROM envase_parametro as env
INNER JOIN areas_lab as lab
ON env.Id_analisis = lab.Id_area
INNER JOIN ViewParametros as pa
ON env.Id_parametro = pa.Id_parametro
INNER JOIN envase as en
ON env.Id_envase = en.Id_envase
INNER JOIN preservacion as pre
ON env.Id_preservador = pre.Id_preservacion
INNER JOIN unidades as uni
ON en.Id_unidad = uni.Id_unidad
/* Lista ViewLoteDetalleDqo */  

CREATE VIEW ViewLoteDetalleDqo as SELECT col.*,sol.Empresa_suc,sol.Clave_norma,sol.Folio_servicio,param.Parametro,param.Limite,control.Control,control.Descripcion,cod.Codigo,cod.Num_muestra FROM lote_detalle_dqo as col
INNER JOIN ViewSolicitud2 as sol
ON col.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON col.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as control
ON col.Id_control = control.Id_control
INNER JOIN codigo_parametro as cod
ON col.Id_codigo = cod.Id_codigo

/* Lista ViewLoteDetalleCloro */ 
CREATE VIEW ViewLoteDetalleCloro as SELECT col.*,sol.Empresa_suc,sol.Clave_norma,sol.Folio_servicio,param.Parametro,control.Control,control.Descripcion,cod.Codigo,cod.Num_muestra FROM lote_detalle_cloro as col
INNER JOIN ViewSolicitud2 as sol
ON col.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON col.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as controlViewPuntoMuestreoSolSir
ON col.Id_control = control.Id_control
INNER JOIN codigo_parametro as cod
ON col.Id_codigo = cod.Id_codigo


/* Lista ViewLoteDetalleNitrogeno */ 

CREATE VIEW ViewLoteDetalleNitrogeno as SELECT col.*,sol.Empresa_suc,sol.Clave_norma,sol.Folio_servicio,param.Parametro,control.Control,control.Descripcion FROM lote_detalle_nitrogeno as col
INNER JOIN ViewSolicitud2 as sol
ON col.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON col.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as control
ON col.Id_control = control.Id_control


/* Lista ViewPuntoMuestreoGen */ 
CREATE VIEW ViewPuntoMuestreoGen as SELECT pu.Id_punto as Id_puntoSol,pu.Id_solPadre,pu.Id_solicitud,pu.Id_muestreo,gen.Id_sucursal,gen.Punto_muestreo 
FROM solicitud_puntos as pu
INNER JOIN puntos_muestreogen as gen
ON pu.Id_muestreo = gen.Id_punto

/*Lista ViewCodigoParametro */

-- CREATE VIEW ViewCodigoParametro AS SELECT cod.*,us.name,us.iniciales,sol.Folio_servicio,sub.Id_subnorma,sub.Id_norma,sub.Norma,sub.Clave,pa.Parametro, pa.Id_simbologia, pa.Simbologia, pa.Id_tipo_formula,pa.Tipo_formula, pa.Unidad, pa.Metodo_prueba, pa.Clave_metodo , pa.Id_area,pa.Limite FROM codigo_parametro as cod
-- INNER JOIN solicitudes as sol
-- ON cod.Id_solicitud = sol.Id_solicitud
-- INNER JOIN sub_normas as sub
-- ON sol.Id_subnorma = sub.Id_subnorma
-- INNER JOIN ViewParametros as pa
-- ON cod.Id_parametro = pa.Id_parametro
-- INNER JOIN users as us
-- ON cod.Analizo = us.id

CREATE VIEW ViewCodigoParametro AS SELECT cod.*,us.name,us.iniciales,sol.Folio_servicio,sol.Siralab,sub.Id_subnorma,sub.Id_norma,sub.Norma,
sub.Clave,pa.Parametro, pa.Id_simbologia, pa.Simbologia, pa.Id_tipo_formula,pa.Tipo_formula, 
pa.Unidad, pa.Metodo_prueba, pa.Clave_metodo,pa.Id_tecnica,pa.Tecnica,pa.Id_simbologia_info,pa.Descripcion2,pa.Simbologia_inf , pa.Id_area,pa.Limite, pro.Hora_recepcion,
pro.Hora_entrada, pro.Empresa,sol.Padre,sol.Hijo
FROM codigo_parametro as cod
INNER JOIN solicitudes as sol
ON cod.Id_solicitud = sol.Id_solicitud
INNER JOIN sub_normas as sub
ON sol.Id_subnorma = sub.Id_subnorma
INNER JOIN ViewParametros as pa
ON cod.Id_parametro = pa.Id_parametro
INNER JOIN users as us
ON cod.Analizo = us.id
INNER JOIN proceso_analisis as pro
ON sol.Id_solicitud = pro.Id_solicitud

/* ViewCodigoParametroSol */
CREATE VIEW ViewCodigoParametroSol as SELECT cod.*,sol.Folio_servicio,pa.Parametro,pa.Unidad FROM codigo_parametro as cod
INNER JOIN ViewSolicitud2 as sol
ON cod.Id_solicitud = sol.Id_solicitud
INNER JOIN ViewParametros as pa
ON cod.Id_parametro = pa.Id_parametro
/* Lista ViewPlanPaquete */
CREATE VIEW ViewPlanPaquete as SELECT p.*,lab.Area,lab.Id_responsable,lab.Parametro,lab.Reportes,us.name,us.firma,us.iniciales,e.Nombre as Envase,e.Volumen, u.Unidad FROM plan_paquete as p
INNER JOIN areas_lab as lab
ON p.Id_area = lab.Id_area
INNER JOIN envase as e
ON p.Id_recipiente = e.Id_envase
INNER JOIN unidades as u
ON u.Id_unidad = e.Id_unidad
INNER JOIN users as us
ON us.id = lab.Id_responsable
/* Lista ViewPlanComplemento */
CREATE VIEW ViewPlanComplemento as SELECT com.*,cam.Complemento FROM plan_complemento as com
INNER JOIN complementos_campo as cam
ON com.Id_complemento = cam.Id_complemento

CREATE VIEW ViewCampoPhCalidad as SELECT c.*,ph.Ph_calidad,ph.Marca,ph.Lote,ph.Inicio_caducidad,ph.Fin_caducidad  FROM campo_phCalidad as c
INNER JOIN ph_calidad as ph
ON c.Id_phCalidad = ph.Id_ph

CREATE VIEW ViewCampoPhTrazable as  SELECT c.*,ph.Ph,ph.Marca,ph.Lote,ph.Inicio_caducidad,ph.Fin_caducidad FROM campo_phTrazable as c
INNER JOIN ph_trazable as ph
ON c.Id_phTrazable = ph.Id_ph

CREATE VIEW ViewCampoConCalidad as SELECT c.*,con.Conductividad,con.Marca,con.Lote,con.Inicio_caducidad,con.Fin_caducidad FROM campo_conCalidad as c
INNER JOIN conductividad_calidad as con
ON c.Id_conCalidad = con.Id_conductividad

CREATE VIEW ViewCampoConTrazable as SELECT c.*,con.Conductividad,con.Marca,con.Lote,con.Inicio_caducidad,con.Fin_caducidad FROM campo_conTrazable as c
INNER JOIN conductividad_trazable as con
ON c.Id_conTrazable = con.Id_conductividad

/* Vista ViewEnvaseParametroSol */
CREATE VIEW ViewEnvaseParametroSol as SELECT env.Id_env,env.Id_analisis,env.Id_parametro,env.Reportes,
env.Id_responsable,env.Id_envase,env.Id_preservador,env.Nombre,env.Volumen,env.Preservacion,env.Unidad as UniEnv,env.Id_area ,
env.Area,pa.Id_solicitud,pa.Extra,pa.Parametro,pa.Area_analisis,pa.Id_area as Id_areaAnalisis,pa.Id_tipo_formula,pa.Asignado,pa.Folio_servicio,
pa.Metodo_prueba,pa.Unidad,env.stdArea
FROM ViewEnvaseParametro as env 
INNER JOIN ViewSolicitudParametros as pa
ON env.Id_parametro = pa.Id_parametro

/* ViewParametroNorma */
CREATE VIEW ViewParametroNorma as SELECT p.*, n.Norma,n.Clave_norma,pa.Id_laboratorio,pa.Sucursal,pa.Id_tipo_formula,pa.Tipo_formula,pa.Id_area,pa.Area_analisis,pa.Id_rama,pa.Rama,pa.Parametro,pa.Id_unidad,pa.Unidad,pa.Descripcion,
pa.Id_metodo,pa.Limite,pa.Id_procedimiento,pa.Id_matriz,pa.Matriz,pa.Id_simbologia,pa.Envase,pa.Simbologia,pa.Simbologia_inf,pa.Id_simbologia_info,pa.Descripcion_sim,pa.Metodo_prueba,pa.Clave_metodo FROM parametros_normas as p
INNER JOIN normas as n
ON p.Id_norma = n.Id_norma
INNER JOIN ViewParametros as pa
ON p.Id_parametro = pa.Id_parametro

/* ViewCotizacionMuestreo */
CREATE VIEW ViewCotizacionMuestreo as SELECT cot.*,est.Nombre as NomEstado ,loc.Nombre as NomLocalidad FROM cotizacion_muestreos as cot
INNER JOIN localidades as loc
ON cot.Localidad = loc.Id_localidad
INNER JOIN estados as est
ON cot.Estado = est.Id_estado

/* ViewDetalleCuerpos */
CREATE VIEW ViewDetalleCuerpos as SELECT det.*,tipo.Cuerpo FROM detalle_tipoCuerpos as det
INNER JOIN tipo_cuerpo as tipo
ON det.Id_tipo = tipo.Id_tipo

-- ViewPrecioPaquete

CREATE VIEW ViewPrecioPaquete as SELECT paq.*, sub.Id_norma,sub.Clave FROM `precio_paquete` as paq
INNER JOIN sub_normas as  sub
ON paq.Id_paquete = sub.Id_subnorma

/* ViewMenuUsuarios */
CREATE VIEW ViewMenuUsuarios as SELECT m.Id_menu,m.Id_user,m.Id_item,it.* FROM menu_usuarios as m
INNER JOIN menu_items as it
ON m.Id_item = it.id

/* ViewLoteDetalleDirectos */

CREATE VIEW ViewLoteDetalleDirectos AS SELECT det.*,sol.Folio_servicio,sol.Num_tomas,sol.Clave_norma,param.Parametro,param.Limite,con.Control,cod.Codigo,cod.Num_muestra FROM lote_detalle_directos as det
INNER JOIN  lote_analisis as lot
ON det.Id_lote = lot.Id_lote
INNER JOIN  ViewSolicitud2 as sol
ON det.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON det.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as con
ON det.Id_control = con.Id_control
INNER JOIN codigo_parametro as cod
ON det.Id_codigo = cod.Id_codigo
/* ViewLoteDetalleDureza */

CREATE VIEW ViewLoteDetalleDureza AS SELECT det.*,sol.Folio_servicio,sol.Num_tomas,sol.Clave_norma,param.Parametro,param.Limite,con.Control,cod.Codigo,cod.Num_muestra FROM lote_detalle_dureza as det
INNER JOIN  lote_analisis as lot
ON det.Id_lote = lot.Id_lote
INNER JOIN  ViewSolicitud2 as sol
ON det.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON det.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as con
ON det.Id_control = con.Id_control
INNER JOIN codigo_parametro as cod
ON det.Id_codigo = cod.Id_codigo

/* ViewLoteDetallePotable */

CREATE VIEW ViewLoteDetallePotable AS SELECT det.*,sol.Folio_servicio,sol.Num_tomas,sol.Clave_norma,param.Parametro,con.Control,cod.Codigo,cod.Num_muestra FROM lote_detalle_potable as det
INNER JOIN  lote_analisis as lot
ON det.Id_lote = lot.Id_lote
INNER JOIN  ViewSolicitud as sol
ON det.Id_analisis = sol.Id_solicitud
INNER JOIN parametros as param
ON det.Id_parametro = param.Id_parametro
INNER JOIN control_calidad as con
ON det.Id_control = con.Id_control
INNER JOIN codigo_parametro as cod
ON det.Id_codigo = cod.Id_codigo

/* ViewParametroUsuarios */

CREATE VIEW ViewParametroUsuarios as SELECT paraUser.Id_user,pa.* FROM parametro_usuarios as paraUser
INNER JOIN ViewParametros as pa
ON paraUser.Id_parametro = pa.Id_parametro


CREATE VIEW ViewLoteDetalleMatraz as SELECT mat.*,lot.Id_detalle,lot.Id_analisis, lot.Id_lote FROM matraz_GA as mat, lote_detalle_ga as lot where mat.Id_matraz = lot.Id_matraz

/* ViewIncidencias */
CREATE VIEW ViewIncidencias AS SELECT inci.*, est.Estado as Estado, menu.title as Modulo, menu2.title as Submodulo, pri.Prioridad as Prioridad FROM incidencias as inci 
INNER JOIN menu_items as menu ON inci.id_modulo = menu.id 
INNER JOIN menu_items as menu2 On inci.Id_submodulo = menu2.id 
INNER JOIN incidencias_estado AS est ON inci.Id_estado = est.Id_estado 
INNER JOIN incidencias_prioridad as pri ON inci.Id_prioridad = pri.Id_prioridad

/* ViewProcesoAnalisis */

CREATE VIEW ViewProcesoAnalisis as SELECT pr.*,sol.Padre,sol.Hijo FROM proceso_analisis as pr
INNER JOIN ViewSolicitud2 as sol
ON pr.Id_solicitud = sol.Id_solicitud


/* ViewSucursalesCliente */

CREATE VIEW ViewSucursalesCliente as SELECT suc.*,cli.Id_intermediario FROM sucursales_cliente as suc
INNER JOIN clientes_general as cli
ON suc.Id_cliente = cli.Id_cliente

/* ViewReportesInformes */
CREATE VIEW ViewReportesInformes as SELECT i.*, u.name as Analizo, us.name as Reviso 
FROM reportes_informes as i 
INNER JOIN users as u
ON i.Id_analizo = u.id
INNER JOIN users as us
on i.Id_reviso = us.id

/* ViewReporteCadena */
CREATE VIEW ViewReportesCadena as SELECT i.*, u.name as Nombre_responsable
FROM reportes_cadena as i 
INNER JOIN users as u
ON i.Responsable = u.id

/* ViewReporteCotizacion */
CREATE VIEW ViewReportesCotizacion as SELECT i.*, u.name as Nombre_responsable
FROM reportes_cotizacion as i 
INNER JOIN users as u
ON i.Id_responsable = u.id

/* ViewReportesInformesMensual */
CREATE VIEW ViewReportesInformesMensual as SELECT i.*, u.name as Analizo, us.name as Reviso 
FROM reportes_informes_mensual as i 
INNER JOIN users as u
ON i.Id_autorizo = u.id
INNER JOIN users as us
on i.Id_reviso = us.id

/* ViewReportesInformesCampo */
CREATE VIEW ViewReportesInformesCampo as SELECT i.*, u.name as Autorizo, us.name as Reviso 
FROM reportes_informes_campo as i 
INNER JOIN users as u
ON i.Id_autorizo = u.id
INNER JOIN users as us
on i.Id_reviso = us.id


/* ViewLimite0012021 */
CREATE VIEW ViewLimite0012021 as SELECT lim.*,cat.Categoria,pa.Parametro,pa.Unidad FROM limite001_2021 as lim
INNER JOIN categoria001_2021 as cat
ON lim.Id_categoria = cat.Id_categoria
INNER JOIN ViewParametros as pa
ON lim.Id_parametro = pa.Id_parametro

/* ViewPlantillasFq */
CREATE VIEW ViewPlantillasFq as SELECT fq.*,p.Parametro FROM plantillas_fq as fq
INNER JOIN parametros as p
ON fq.Id_parametro = p.Id_parametro

/* ViewPlantillasVolumetria */
CREATE VIEW ViewPlantillasVolumetria as SELECT vol.*,p.Parametro FROM plantilla_volumetria as vol
INNER JOIN parametros as p
ON vol.Id_parametro = p.Id_parametro

/* ViewPlantillasVolumetria */
CREATE VIEW ViewPlantillasDirectos as SELECT dir.*,p.Parametro FROM plantilla_directos as dir
INNER JOIN parametros as p
ON dir.Id_parametro = p.Id_parametro

/* ViewPlantillasVolumetria */
CREATE VIEW ViewPlantillasMb as SELECT mb.*,p.Parametro FROM plantilla_mb as mb
INNER JOIN parametros as p
ON mb.Id_parametro = p.Id_parametro

/* ViewPlantillasVolumetria */
CREATE VIEW ViewPlantillasPotable as SELECT pot.*,p.Parametro FROM plantilla_potable as pot
INNER JOIN parametros as p
ON pot.Id_parametro = p.Id_parametro

/* ViewPlantillasVolumetria */
CREATE VIEW ViewPlantillaMetales as SELECT met.*,p.Parametro FROM plantillas_metales as met
INNER JOIN parametros as p
ON  met.Id_parametro = p.Id_parametro


/* ViewMatrazConMuestra */
CREATE VIEW  ViewMatrazConMuestra as SELECT ga.*,ma.Peso,ma.Min,ma.Max,ma.Estado FROM ViewLoteDetalleGA as ga
INNER JOIN matraz_GA as ma
ON ga.Id_matraz = ma.Id_matraz

/* ViewCotizacionList */

CREATE VIEW ViewCotizacionList as SELECT cot.*,nom.Norma,nom.Clave_norma,des.Descarga,est.Estado,usr.name,usr2.name as name_create,usr2.name as name_mod FROM cotizacion as cot
INNER JOIN normas as nom
ON cot.Id_norma = nom.Id_norma
INNER JOIN tipo_descargas as des
ON cot.Tipo_descarga = des.Id_tipo
INNER JOIN cotizacion_estado as est
ON cot.Estado_cotizacion = est.Id_estado
INNER JOIN users as usr
ON cot.Creado_por = usr.id
INNER JOIN users as usr2
ON cot.Actualizado_por = usr2.id


/* ViewParametrosProceso */ 
CREATE VIEW ViewParametroProceso as SELECT cod.*,par.Parametro,par.Id_tipo_formula,pro.Cliente,pro.Empresa 
FROM codigo_parametro as cod
INNER JOIN parametros as par
ON cod.Id_parametro = par.Id_parametro
INNER JOIN proceso_analisis as pro
ON cod.Id_solicitud = pro.Id_solicitud

/* ViewParametroProcesoMetales */
CREATE VIEW  ViewParametroProcesoMetales as SELECT * FROM `ViewParametroProceso` WHERE Id_tipo_formula = 20 OR Id_tipo_formula = 21 OR Id_tipo_formula = 22 
OR Id_tipo_formula = 23 OR Id_tipo_formula = 24 GROUP BY Id_solicitud;