<?php namespace Strana;

class RecordSet implements \Iterator
{

    /**
     * @var array
     */
    protected $records;

    /**
     * @var
     */
    protected $total;

    /**
     * @var
     */
    protected $position;

    /**
     * @var string
     */
    protected $links;

    /**
     * @param array $records
     * @param $total
     * @param null $links
     */
    public function __construct(Array $records, $total, $links = null)
    {
        $this->records = $records;
        $this->total = $total;
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function records()
    {
        return $this->records;
    }

    /**
     * @return int
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * @return mixed|void
     */
    public function rewind()
    {
        return reset($this->records);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->records);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->records);
    }

    /**
     * @return mixed|void
     */
    public function next()
    {
        return next($this->records);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return key($this->records) !== null;
    }

    /**
     * @param $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }

    /**
     * @return null|string
     */
    public function links()
    {
        return $this->links;
    }


    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->links();
    }
}