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

/* Vista Lista clientes generales*/

CREATE VIEW ViewGenerales as SELECT
	gen.Id_cliente_general,gen.Id_cliente,cli.created_at,cli.updated_at,cli.deleted_at,gen.Empresa,gen.Alias,
    gen.Id_intermediario,cli2.Nombres,cli2.A_paterno,cli2.A_materno,cli2.RFC
FROM clientes_general as gen
INNER JOIN clientes as cli
ON gen.Id_cliente = cli.Id_cliente
INNER JOIN intermediarios as inter
ON gen.Id_intermediario = inter.Id_intermediario
INNER JOIN clientes as cli2
ON inter.Id_cliente = cli2.Id_cliente

/* Vista Lista parametros */
CREATE VIEW ViewParametros as SELECT param.Id_parametro,param.Id_laboratorio,lab.Sucursal,param.Id_tipo_formula,tipo.Tipo_formula,param.Id_rama,ram.Rama,param.Parametro,
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
CREATE VIEW ViewNormaParametro as SELECT n.Id_norma_param,n.Id_norma,nor.Norma,nor.Clave,n.Id_parametro,p.Parametro,p.Id_matriz,mat.Matriz FROM norma_parametros as n
INNER JOIN sub_normas as nor
ON n.Id_norma = nor.Id_subnorma
INNER JOIN parametros as p
ON n.Id_parametro = p.Id_parametro
INNER JOIN matriz_parametros as mat
ON p.Id_matriz = mat.Id_matriz_parametro

/* Vista Lista View_limite001*/
CREATE VIEW ViewLimite001 as SELECT lim.Id_limite,lim.Id_tipo,tipo.Categoria,lim.Id_parametro,pa.Parametro,lim.Prom_Mmax,lim.Prom_Mmin,lim.Prom_Dmax,lim.Prom_Dmin FROM limitepnorma_001 as lim
INNER JOIN detalles_tipoCuerpo as tipo
ON lim.Id_tipo = tipo.Id_detalle
INNER JOIN parametros as pa
ON lim.Id_parametro = pa.Id_parametro

/*Vista   Lista precio catalgo*/
CREATE VIEW ViewPrecioCat as SELECT cat.Id_precio,cat.Id_parametro,par.Parametro,par.Tipo_formula,par.Rama,par.Unidad,
par.Descripcion,par.Limite,par.Procedimiento,par.Matriz,par.Metodo_prueba,cat.Id_laboratorio,lab.Laboratorio,cat.Precio,
cat.created_at,cat.updated_at,cat.deleted_at
FROM precio_catalogo as cat
INNER JOIN ViewParametros as par
ON cat.Id_parametro = par.Id_parametro
INNER JOIN laboratorios as lab
ON cat.Id_laboratorio = lab.Id_laboratorio