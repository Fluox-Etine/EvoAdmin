<?php

namespace app\http\generate\service\backend;

class RouteService
{

    /**
     * 生成路由文件
     * @param array $params
     * @return string
     */
    public static function handleRoute(array $params): string
    {
        // 需要替换的变量
        $needReplace = [
            '{CLASS_COMMENT}',
            '{DATE}',
            '{GROUP_NAME}',
            '{FRONT_ROUTES}',
        ];

        $upperCameNameArray = explode('_', uncamelize($params['upperCameName']));
        // 等待替换的内容
        $waitReplace = [
            $params['classComment'],
            $params['date'],
            $upperCameNameArray[0],
            self::getFrontRoutes($params, $upperCameNameArray),
        ];

        $templatePath = GenerateService::getTemplatePath('php/route');
        // 替换内容
        return GenerateService::replaceFileData($needReplace, $waitReplace, $templatePath);
    }


    /**
     * 接口路由
     * @param array $params
     * @param array $upperCameNameArray
     * @return string
     */
    protected static function getFrontRoutes(array $params, array $upperCameNameArray): string
    {
        $frontRoutes = '';
        if (count($upperCameNameArray) > 1) {
            $frontRoutes .= "Route::group('" . $upperCameNameArray[1] . "',function(){" . PHP_EOL;
        }
        $controller = GenerateService::getNameSpaceContent($params['moduleName'], $params['classDir'], $params['upperCameName'], 'controller');

        $controller = substr($controller, 10, strlen($controller) - 11);
        $controller = $controller . '\\' . GenerateService::getLastCamelCaseWord($params['upperCameName'])[0] . 'Controller';
        $action = $params['controller'];
        $routesTemplate = "       Route::post('/%s',['%s','%s']);";
        foreach (['list' => '列表', 'create' => '创建', 'update' => '更新', 'delete' => '删除', 'detail' => '详情'] as $key => $value) {
            if (!empty($action[$key])) {
                $endpoint = strtolower($key);
                $frontRoutes .= "       // {$value}接口" . PHP_EOL;
                $frontRoutes .= sprintf($routesTemplate, $endpoint, $controller, $key) . PHP_EOL;
            }
        }
        $frontRoutes .= "   });";
        var_dump($frontRoutes);
        return $frontRoutes;
    }
}