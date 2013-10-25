<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore;

use OpenCloud\Common\Collection;
use OpenCloud\Common\Service\AbstractService as CommonAbstractService;

/**
 * An abstract base class for common code shared between ObjectStore\Service
 * (container) and ObjectStore\CDNService (CDN containers).
 */
abstract class AbstractService extends CommonAbstractService
{
    const DEFAULT_NAME = 'cloudFiles';

    const MAX_CONTAINER_NAME_LENGTH = 256;
    const MAX_OBJECT_NAME_LEN       = 1024;
    const MAX_OBJECT_SIZE           = 5102410241025;

    /**
     * List all available containers. If called by a CDN service, it returns CDN-enabled; if called by a regular
     * service, normal containers are returned.
     *
     * @param array $filter
     * @return Collection
     */
    public function listContainers(array $filter = array())
    {
        $filter['format'] = 'json';

        $containers = $this->getClient()
            ->get($this->getUrl(null, $filter))
            ->send()
            ->getDecodedBody();
        
        $class = ($this instanceof Service) ? 'Container' : 'CDNContainer';

        return new Collection($this, __NAMESPACE__ . '\\Resource\\' . $class, $containers);
    }

    /**
     * @return Resource\Account
     */
    public function getAccount()
    {
        return new Resource\Account($this);
    }
    
}
