<?php

namespace Elastica\Connection\Strategy;

/**
 * Description of StrategyFactory
 *
 * @author chabior
 */
class StrategyFactory
{
    /**
     * 
     * @param mixed|Closure|String|StrategyInterface $strategyName
     * @return \Elastica\Connection\Strategy\StrategyInterface
     * @throws \InvalidArgumentException
     */
    public static function create($strategyName)
    {
        $strategy = null;
        if ($strategyName instanceof StrategyInterface) {
            $strategy = $strategyName;
        } else if (CallbackStrategy::isValid($strategyName)) {
            $strategy = new CallbackStrategy($strategyName);
        } else if (class_exists($strategyName)) {
            $strategy = new $strategyName();
        } else if (is_string($strategyName)) {
            $pathToStrategy = '\\Elastica\\Connection\\Strategy\\'.$strategyName;
            if (class_exists($pathToStrategy)) {
                $strategy = new $pathToStrategy();
            }
        }
        
        if (!$strategy instanceof StrategyInterface) {
            throw new \InvalidArgumentException('Can\'t load strategy class');
        }
        
        return $strategy;
    }
    /**
     * 
     * @return \Elastica\Connection\Strategy\Simple
     */
    public static function getSimpleStrategy()
    {
        return new Simple();
    }
}
