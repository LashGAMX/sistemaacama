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
    inter.Tel_oficina,
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
CREATE VIEW ViewParametros as SELECT param.Id_parametro,param.Id_laboratorio,lab.Sucursal,param.Id_tipo_formula,tipo.Tipo_formula,param.Id_user_c,param.Id_user_m,param.Id_rama,ram.Rama,param.Parametro,
param.Id_unidad,uni.Unidad,uni.Descripcion,param.Id_metodo,param.Id_norma,param.Limite,param.Id_procedimiento,pro.Procedimiento,param.Id_matriz,mat.Matriz,param.Id_simbologia,
sim.Simbologia,sim.Descripcion as Descripcion_sim
,nor.Norma,nor.Clave_norma,met.Metodo_prueba,met.Clave_metodo,param.Precio,param.F_inicio_vigencia,param.F_fin_vigencia,param.created_at,param.updated_at,
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
INNER JOIN normas as nor
ON param.Id_norma = nor.Id_norma
INNER JOIN procedimiento_analisis as pro
ON param.Id_procedimiento = pro.Id_procedimiento
INNER JOIN matriz_parametros as mat
ON param.Id_matriz = mat.Id_matriz_parametro
INNER JOIN simbologia_parametros as sim
ON param.Id_simbologia = sim.Id_simbologia

/* Vista Lista norma-parametros */
CREATE VIEW ViewNormaParametro as SELECT n.Id_norma_param,n.Id_norma,nor.Norma,nor.Clave,n.Id_parametro,p.Parametro,p.Id_matriz,mat.Matriz,p.Id_simbologia,sim.Simbologia,sim.Descripcion 
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
CREATE VIEW ViewLimite001 as SELECT lim.Id_limite,lim.Id_tipo,tipo.Categoria,lim.Id_parametro,pa.Parametro,lim.Prom_Mmax,lim.Prom_Mmin,lim.Prom_Dmax,lim.Prom_Dmin FROM limitepnorma_001 as lim
INNER JOIN detalles_tipoCuerpo as tipo
ON lim.Id_tipo = tipo.Id_detalle
INNER JOIN parametros as pa
ON lim.Id_parametro = pa.Id_parametro

/*Vista   Lista precio catalgo*/
CREATE VIEW ViewPrecioCat as SELECT cat.Id_precio,cat.Id_parametro,cat.Id_user_c,cat.Id_user_m,par.Parametro,par.Tipo_formula,par.Rama,par.Unidad,
par.Descripcion,par.Limite,par.Procedimiento,par.Matriz,par.Metodo_prueba,cat.Id_laboratorio,lab.Sucursal,cat.Precio,
cat.created_at,cat.updated_at,cat.deleted_at
FROM precio_catalogo as cat
INNER JOIN ViewParametros as par
ON cat.Id_parametro = par.Id_parametro
INNER JOIN sucursales as lab
ON cat.Id_laboratorio = lab.Id_sucursal

/* Lista precio paquete */
CREATE VIEW ViewPrecioPaq as SELECT
p.Id_precio,p.Id_paquete,sub.Id_norma,sub.Norma,sub.Clave,p.Precio,p.Id_tipo,p.created_at,p.updated_at,p.deleted_at
FROM precio_paquete as p
INNER JOIN sub_normas as sub
ON p.Id_paquete = sub.Id_subnorma

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
cot.Id_cotizacion,cot.Id_intermedio,inter.Nombres as NomInter,inter.A_paterno as ApeInter,cot.Id_cliente,cot.Nombre,cot.Direccion,
cot.Atencion,cot.Telefono,cot.Correo,cot.Tipo_servicio,ser.Servicio,cot.Tipo_descarga,des.Descarga,
cot.Id_norma,nor.Norma,nor.Clave_norma,cot.Id_subnorma,cot.Fecha_muestreo,cot.Frecuencia_muestreo,cot.Tomas,
cot.Tipo_muestra,cot.Promedio,cot.Numero_puntos,cot.Tipo_reporte,
cot.Tiempo_entrega,cot.Observacion_interna,cot.Observacion_cotizacion,cot.Folio_servicio,
cot.Folio,cot.Fecha_cotizacion,cot.Metodo_pago,cot.Precio_analisis,cot.Descuento,cot.Precio_muestreo,cot.Sub_total,
cot.Costo_total,cot.Estado_cotizacion,est.Estado,est.Descripcion as Descripcion_estado,
cot.Supervicion,cot.Creado_por,usr.name as NameC,cot.Actualizado_por,usr2.name as NameA,cot.created_at,cot.updated_at,cot.deleted_at
FROM cotizacion as cot
INNER JOIN normas as nor
ON cot.Id_norma = nor.Id_norma
INNER JOIN ViewIntermediarios as inter
ON cot.Id_intermedio = inter.Id_cliente
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

/* Lista cotizacion parametros */
CREATE VIEW ViewCotParam as SELECT 
param.Id_parametro,param.Id_cotizacion,param.Id_subnorma,param.Extra,param.created_at,param.updated_at,
param.deleted_at,
p.Parametro,p.Matriz,p.Simbologia,p.Metodo_prueba,p.Limite,p.Unidad
FROM cotizacion_parametros as param
INNER JOIN ViewParametros as p
ON param.Id_subnorma = p.Id_parametro

/* Lista solicitudes */
CREATE VIEW ViewSolicitud as SELECT 
sol.Id_cotizacion,sol.Folio,sol.Folio_servicio,sol.Id_solicitud,sol.Id_intermediario,inter.Nombres,inter.A_paterno,
sol.Id_cliente,gen.Empresa,sol.Id_sucursal,suc.Empresa as Empresa_suc,suc.Estado,sol.Id_direccion,dir.Direccion,
sol.Id_contacto,con.Nombres as Nom_con,con.A_paterno as Nom_pat,sol.Atencion,sol.Observacion,sol.Id_servicio,ser.Servicio,ser.Descripcion,
sol.Id_descarga,des.Descarga,sol.Id_norma,nor.Norma,nor.Clave_norma,sol.Id_subnorma,sub.Norma as Nor_sub,sub.Clave,sol.Fecha_muestreo,
sol.Id_muestreo,sol.Num_tomas,sol.Id_muestra,sol.Id_promedio,sol.Id_reporte,sol.created_at,sol.updated_at,sol.deleted_at
FROM solicitudes  as sol
INNER JOIN ViewIntermediarios as inter
ON inter.Id_cliente = sol.Id_intermediario
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

/* Lista Solicitud parametros */
CREATE VIEW ViewSolicitudParametros as SELECT sol.Id_solicitud,sol.Extra,pa.Id_parametro,pa.Parametro FROM solicitud_parametros as sol
INNER JOIN ViewParametros as pa
ON sol.Id_subnorma = pa.Id_parametro

/* Lista Puntos Solicitud Generales */
CREATE VIEW ViewPuntoGenSol as SELECT sol.Id_punto,sol.Id_solicitud,sol.Id_muestreo,puntos.Id_sucursal,puntos.Punto_muestreo FROM solicitud_puntos as sol
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

/* Lista ViewSolicitudGenerada */
CREATE VIEW ViewSolicitudGenerada as SELECT sol.*,gen.Id_solicitudGen,gen.Captura,gen.Id_muestreador,gen.Estado as StdSol FROM solicitudes_generadas as gen
INNER JOIN ViewSolicitud as sol
ON gen.Id_solicitud = sol.Id_solicitud

/* Campo generales */ 
CREATE VIEW ViewCampoGenerales as SELECT c.Id_general,c.Id_solicitud,c.Captura,c.Id_equipo,t.Id_muestreador,t.Equipo,t.Marca,t.Modelo,t.Serie,c.Temperatura_a,c.Temperatura_b,c.Latitud,c.Longitud,c.Altitud,c.Pendiente,c.Criterio,c.Supervisor,c.created_at,c.updated_at,c.deleted_at 
FROM campo_generales as c
INNER JOIN termometro_campo as t
ON c.Id_equipo = t.Id_termometro