import Registry from '@maelstrom-cms/toolkit/js/support/Registry';
import ButtonColumn from './components/ButtonColumn';
import CertificateReport from './components/CertificateReport';
import DnsReport from './components/DnsReport';
import RobotsReport from './components/RobotsReport';
import UptimeReport from './components/UptimeReport';
import OpenGraph from './components/OpenGraph';
import CronReport from './components/CronReport';
import CrawlReport from './components/CrawlReport';
import VisualDiff from './components/VisualDiff';
import AOS from 'aos';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

Registry.register({
    VisualDiff,
    OpenGraph,
    DnsReport,
    ButtonColumn,
    RobotsReport,
    UptimeReport,
    CertificateReport,
    CronReport,
    CrawlReport,
});

require('@maelstrom-cms/toolkit');

window.onload = AOS.init();
