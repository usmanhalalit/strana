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

    public function rewind()
    {
        return reset($this->records);
    }

    public function current()
    {
        return current($this->records);
    }

    public function key()
    {
        return key($this->records);
    }

    public function next()
    {
        return next($this->records);
    }

    public function valid()
    {
        return key($this->records) !== null;
    }

    public function setLinks($links)
    {
        $this->links = $links;
    }

    public function links()
    {
        return $this->links;
    }

    public function __toString()
    {
        return $this->links();
    }
}