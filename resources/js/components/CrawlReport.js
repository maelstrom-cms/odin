import React from 'react';
import { Card, Button, Spin, Table, Tag, Popconfirm, message } from 'antd';
import { formatDateTime, truncate } from '../helpers';
import { NoData } from '../helpers'

export default class CrawlReport extends React.Component {

    state = {
        website: JSON.parse(this.props.website),
        loading: true,
        pages: [],
        count: 0,
    };

    componentDidMount() {
        this.update();
    }

    update = async (showLoader = true, refresh = false) => {
        await this.setState({
            loading: showLoader,
        });

        let endpoint = this.props.endpoint;

        if (refresh) {
            endpoint += '?refresh=1';
        }

        const response = (await window.axios.get(endpoint)).data;

        this.setState({
            ...response,
            loading: false,
        });
    };

    forceUpdate = () => {
        this.update(true, true);
    };

    renderPages() {
        const tag = function (row) {
            const color = row.response.includes('200') ? 'green' : 'red';

            return <Tag color={ color }>
                { row.response }
            </Tag>
        };

        const url = function (row) {
            return <a className="block" style={{ maxWidth: 500 }} rel="noreferrer noopener" target="_blank" href={ row.url }>{ row.url }</a>
        };

        const summary = function (row) {
            if (row.exception) {
                return <span>{ truncate(row.exception) }</span>
            }

            return <span>{ truncate(row.messages) }</span>
        };

        const expanded = function (row) {
            return <div>
                <div>
                    <h4>Response: { row.response || 'Unknown' }</h4>
                    <h4>Exceptions: { row.exception || 'N/A' }</h4>
                    <pre style={{
                        overflowX: 'auto',
                        whiteSpace: 'pre-wrap',
                        wordWrap: 'break-word',
                    }} className="mt-4 shadow block max-w-full p-6 bg-white m-0" dangerouslySetInnerHTML={{ __html: (row.messages) }} />
                </div>
            </div>
        };

        const removePage = async (id, context) => {
            await axios.delete(`${context.props.endpoint}/${id}/delete`);
            this.update(false);
        };

        const remove = function (row, context) {
            return <Popconfirm placement="left" title="Are you sure you want to delete this page?" onConfirm={ () => removePage(row.id, context) } okText="Yes" cancelText="No">
                <Button size="small" shape="circle" icon="delete" type="danger" />
            </Popconfirm>
        };

        return (
            <Table
                pagination={ false }
                width="100%"
                dataSource={ this.state.pages }
                rowKey="id"
                expandedRowRender={ expanded }
                columns={[
                    { key: 'response', title: 'Response', dataIndex: 'response', render: (text, row) => tag(row) },
                    { key: 'updated_at', title: 'Found on', dataIndex: 'updated_at', render: text => formatDateTime(text) },
                    { key: 'url', title: 'URL', dataIndex: 'url', render: (text, row) =>  url(row) },
                    { key: 'summary', title: 'Messages', dataIndex: 'summary', render: (text, row) =>  summary(row) },
                    { key: 'id', title: '', dataIndex: 'id', render: (text, row) =>  remove(row, this)},
                ]}
            />
        )
    };

    reCrawl = async () => {
        await this.setState({
            loading: true,
        });

        await axios.get(`${this.props.endpoint}/scan`);

        message.success('Crawl requested, please check back soon for an update.');

        this.setState({
            loading: false,
        });
    };

    renderExtras = () => {
        const { count, pages, loading } = this.state;

        return <div className="flex items-center">
            <span>Showing { pages.length } pages of { count } crawled.</span>
            <Button onClick={ this.reCrawl } loading={ loading } className="ml-2">Request Crawl</Button>
            <Button onClick={ this.forceUpdate } loading={ loading } className="ml-4" shape="circle" icon="sync" size="small" type="primary" />
        </div>
    };

    renderReport = () =>{
        if (!this.state.pages.length) {
            return <NoData />
        }

        return this.renderPages();
    };

    renderBusy = () => {
        return <div className="p-6">
            <Spin />
        </div>
    };

    render() {
        const { loading } = this.state;

        return <div>
            <Card
                title="Crawl Report"
                extra={ this.renderExtras() }
                bodyStyle={{ padding: 0, borderTop: '1px solid #e8e8e8', borderBottom: 0 }}
            >
                { !loading ? this.renderReport() : this.renderBusy() }
            </Card>
        </div>
    }
}
