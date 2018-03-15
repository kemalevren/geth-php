<?php
/*
 * (c) Kemal Evren <hi@kemalevren.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace kemalevren\Geth;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class JsonRpc
{
    protected static $_defaultOptions = [
        'version' => '2.0',
        'host' => '127.0.0.1',
        'port' => 8545,
        'assoc' => true
    ];
    protected $_options = [];
    protected $_address = null;
    protected $_id = 0;
    protected $_request = null;
    protected $_client;

    /**
     * Service constructor.
     * @param string|array $options (optional)
     */
    public function __construct($options = null)
    {
        if ($options) {
            if (!is_array($options)) {
                if (is_int($options)) {
                    $options = ['port' => $options];
                }
            }
        } else {
            $options = [];
        }

        $this->_options = array_merge(self::$_defaultOptions, $options);
        $this->_address = 'http://' . $this->_options['host'] . ':' . $this->_options['port'];
        $this->_client = new Client();
    }

    /**
     * Returns the latest transaction ID
     * @return int
     */
    public function getLatestId()
    {
        return $this->_id;
    }

    public function setOptions($options = null)
    {
        if ($options) {
            if (!is_array($options)) {
                if (is_int($options)) {
                    $options = ['port' => $options];
                }
            }
        } else {
            $options = [];
        }

        $this->_options = array_merge(self::$_defaultOptions, $options);
        $this->_address = 'http://' . $this->_options['host'] . ':' . $this->_options['port'];
        $this->_client = new Client();
    }

    /**
     * Magic handler for RPC methods
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments = [], $options = [])
    {
        $id = ++$this->_id;
        $data = [
            'jsonrpc' => $this->_options['version'],
            'method' => $name,
            'params' => $arguments,
            'id' => $id,
        ];
        $json = json_encode($data);
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($json)
        ];

        $this->_request = new Request('POST', $this->_address, $headers, $json);
        $response = json_decode($this->_client->send($this->_request, $options)->getBody()->getContents(), $this->_options['assoc']);

        if ($this->_options['assoc']) {
            if (isset($response['error'])) {
                throw new Exception($response['error']['message'], $response['error']['code']);
            }
            if (!array_key_exists('result', $response)) {
                return null;
            }
            return $response['result'];
        } else {
            if (isset($response->error)) {
                throw new Exception($response->error->message, $response->error->code);
            }
            if (!array_key_exists('result', $response)) {
                return null;
            }
            return $response->result;
        }
    }

    /**
     * @return float|int
     * @throws Exception
     */
    function net_peerCount()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function eth_syncing()
    {
        $r = $this->__call(__FUNCTION__, func_get_args());
        foreach ($r as &$v) {
            $v = hexdec($v);
        }
        return $r;
    }

    /**
     * @return float|int
     * @throws Exception
     */
    function eth_hashrate()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @return float|int
     * @throws Exception
     */
    function eth_gasPrice()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @return float|int
     * @throws Exception
     */
    function eth_blockNumber()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $address
     * @param string $tag
     * @return float|int
     * @throws Exception
     */
    function eth_getBalance($address, $tag = 'latest')
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $address
     * @return float|int
     * @throws Exception
     */
    function eth_getTransactionCount($address)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $hash
     * @return float|int
     * @throws Exception
     */
    function eth_getBlockTransactionCountByHash($hash)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $tag
     * @return float|int
     * @throws Exception
     */
    function eth_getBlockTransactionCountByNumber($tag)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $hash
     * @return float|int
     * @throws Exception
     */
    function eth_getUncleCountByBlockHash($hash)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $tag
     * @return float|int
     * @throws Exception
     */
    function eth_getUncleCountByBlockNumber($tag)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $transaction
     * @param $tag
     * @return float|int
     * @throws Exception
     */
    function eth_estimateGas($transaction, $tag)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $options
     * @return float|int
     * @throws Exception
     */
    function eth_newFilter($options)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @return float|int
     * @throws Exception
     */
    function eth_newBlockFilter()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @return float|int
     * @throws Exception
     */
    function eth_newPendingTransactionFilter()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @param $options
     * @return float|int
     * @throws Exception
     */
    function shh_newFilter($options)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function web3_clientVersion()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    function web3_sha3($data)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function net_version()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function net_listening()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return float|int
     * @throws Exception
     */
    function eth_protocolVersion()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function eth_coinbase()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function eth_mining()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function eth_accounts()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $address
     * @param $quantity
     * @param $tag
     * @return mixed
     * @throws Exception
     */
    function eth_getStorageAt($address, $quantity, $tag)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $address
     * @param $tag
     * @return mixed
     * @throws Exception
     */
    function eth_getCode($address, $tag)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $account
     * @param $message
     * @return mixed
     * @throws Exception
     */
    function eth_sign($account, $message)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $transaction
     * @return mixed
     * @throws Exception
     */
    function eth_sendTransaction($transaction)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    function eth_sendRawTransaction($data)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $transaction
     * @param $tag
     * @return mixed
     * @throws Exception
     */
    function eth_call($transaction, $tag)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $hash
     * @param $full
     * @return mixed
     * @throws Exception
     */
    function eth_getBlockByHash($hash, $full)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getBlockByNumber($tag, $full)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function eth_getTransactionByHash()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $hash
     * @param $quantity
     * @return mixed
     * @throws Exception
     */
    function eth_getTransactionByBlockHashAndIndex($hash, $quantity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $tag
     * @param $quantity
     * @return mixed
     * @throws Exception
     */
    function eth_getTransactionByBlockNumberAndIndex($tag, $quantity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $hash
     * @return mixed
     * @throws Exception
     */
    function eth_getTransactionReceipt($hash)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $hash
     * @param $quantity
     * @return mixed
     * @throws Exception
     */
    function eth_getUncleByBlockHashAndIndex($hash, $quantity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $tag
     * @param $position
     * @return mixed
     * @throws Exception
     */
    function eth_getUncleByBlockNumberAndIndex($tag, $position)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function eth_getCompilers()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $code
     * @return mixed
     * @throws Exception
     */
    function eth_compileSolidity($code)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $code
     * @return mixed
     * @throws Exception
     */
    function eth_compileLLL($code)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $code
     * @return mixed
     * @throws Exception
     */
    function eth_compileSerpent($code)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function eth_uninstallFilter($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function eth_getFilterChanges($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function eth_getFilterLogs($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $options
     * @return mixed
     * @throws Exception
     */
    function eth_getLogs($options)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getWork()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $work
     * @return mixed
     * @throws Exception
     */
    function eth_submitWork($work)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $hashrate
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function eth_submitHashrate($hashrate, $id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function shh_version()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $message
     * @return mixed
     * @throws Exception
     */
    function shh_post($message)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function shh_newIdentity()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $identity
     * @return mixed
     * @throws Exception
     */
    function shh_hasIdentity($identity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    function shh_newGroup()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $identity
     * @return mixed
     * @throws Exception
     */
    function shh_addToGroup($identity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function shh_uninstallFilter($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function shh_getFilterChanges($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function shh_getMessages($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }
}
