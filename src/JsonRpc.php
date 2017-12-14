<?php
/*
 * (c) Kemal Evren <hi@kemalevren.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace kemalevren\Geth;

use Guzzle\Http\Client;

class JsonRpc
{
    protected static $_defaultOptions = [
        // Geth JSON-RPC version
        'version' => '2.0',
        // Host part of address
        'host' => '127.0.0.1',
        // Port part of address
        'port' => 8545,
        // Return results as associative arrays instead of objects
        'assoc' => true
    ];
    protected $_options = [];
    protected $_address = null;
    protected $_id = 0;
    protected $client = null;

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
                } else {
                    if (preg_match('/^([^\:]+)\:([\d]+)$/', $options, $match)) {
                        $options = ['host' => $match[1], 'port' => $match[2]];
                    } else {
                        $options = ['host' => $options];
                    }
                }
            }
        } else {
            $options = [];
        }
        $this->_options = array_merge(self::$_defaultOptions, $options);
        $this->_address = 'http://' . $this->_options['host'] . ':' . $this->_options['port'];
        $this->client = Client([
            // Base URI is used with relative requests
            'base_uri' => $this->_address,
            // You can set any number of default request options.
            'timeout'  => 2.0
        ]);
    }

    /**
     * Returns the latest transaction ID
     * @return int
     */
    public function getLatestId()
    {
        return $this->_id;
    }

    /**
     * Magic handler for RPC methods
     * @param string $name
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $method = 'POST', $arguments = [])
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

        $response = $this->client->request($method, $headers, ['body' => $json]);

        print_r($response);
        exit;

        $curl = curl_init($this->_address);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ]);
        $result = curl_exec($curl);
        if (!$result) {
            throw new \RuntimeException(curl_error($curl), curl_errno($curl));
        }
        curl_close($curl);
        $data = json_decode($result, $this->_options['assoc']);
        if ($this->_options['assoc']) {
            if (isset($data['error'])) {
                throw new Exception($data['error']['message'], $data['error']['code']);
            }
            if (!array_key_exists('result', $data)) {
                return null;
            }
            return $data['result'];
        } else {
            if (isset($data->error)) {
                throw new Exception($data->error->message, $data->error->code);
            }
            if (!array_key_exists('result', $data)) {
                return null;
            }
            return $data->result;
        }
    }

    /**
     * @inheritDoc
     */
    function net_peerCount()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    function eth_hashrate()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_gasPrice()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_blockNumber()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_getBalance($address, $tag = 'latest')
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_getTransactionCount($address)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_getBlockTransactionCountByHash($hash)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_getBlockTransactionCountByNumber($tag)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_getUncleCountByBlockHash($hash)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_getUncleCountByBlockNumber($tag)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_estimateGas($transaction, $tag)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_newFilter($options)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_newBlockFilter()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_newPendingTransactionFilter()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function shh_newFilter($options)
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function web3_clientVersion()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function web3_sha3($data)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function net_version()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function net_listening()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_protocolVersion()
    {
        return hexdec($this->__call(__FUNCTION__, func_get_args()));
    }

    /**
     * @inheritDoc
     */
    function eth_coinbase()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_mining()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_accounts()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getStorageAt($address, $quantity, $tag)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getCode($address, $tag)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_sign($account, $message)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_sendTransaction($transaction)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_sendRawTransaction($data)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_call($transaction, $tag)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    function eth_getTransactionByHash()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getTransactionByBlockHashAndIndex($hash, $quantity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getTransactionByBlockNumberAndIndex($tag, $quantity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getTransactionReceipt($hash)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getUncleByBlockHashAndIndex($hash, $quantity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getUncleByBlockNumberAndIndex($tag, $position)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getCompilers()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_compileSolidity($code)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_compileLLL($code)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_compileSerpent($code)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_uninstallFilter($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getFilterChanges($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_getFilterLogs($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    function eth_submitWork($work)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function eth_submitHashrate($hashrate, $id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_version()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_post($message)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_newIdentity()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_hasIdentity($identity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_newGroup()
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_addToGroup($identity)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_uninstallFilter($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_getFilterChanges($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * @inheritDoc
     */
    function shh_getMessages($id)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }
}