import React from 'react';
import { Card, Button, Spin, InputNumber, Icon, Table, Tag } from 'antd';
import { formatDateTime, GREEN, RED } from '../helpers';
import { NoData } from '../helpers'

export default class CronReport extends React.Component {

    state = {
        website: JSON.parse(this.props.website),
        loading: true,
        total: 0,
        limit: 20,
        found: 20,
        events: [],
    };

    componentDidMount() {
        this.update();
    }

    update = async () => {
        await this.setState({
            loading: true
        });

        const endpoint = this.props.endpoint;
        const { limit } = this.state;
        const params = { limit };

        const response = (await window.axios.get(endpoint, { params })).data;
        response.limit = response.limit > response.found ? response.found : response.limit;

        this.setState({
            ...response,
            loading: false,
        });
    };

    renderFilter() {
        return (
            <>
                <span className="mr-1">Showing <InputNumber min={ 1 } max={ this.state.total } defaultValue={ this.state.limit } value={ this.state.limit } onChange={ limit => this.setState({ limit })} /> of { this.state.total }</span>
                <Button loading={ this.state.loading } onClick={ this.update }>Refresh</Button>
            </>
        )
    }

    renderEvents() {
        const tag = function (row) {
            const color = row.event === 'started' ? 'green' : 'red';
            const icon = row.event === 'started' ? 'play-circle' : 'stop';

            return <Tag color={ color }>
                <Icon type={ icon } style={{ marginRight: 5 }} />
                { row.event }
            </Tag>
        };

        const payload = function (row) {
            const color = row.event === 'started' ? GREEN : RED;

            return <span style={{ color }}>{ JSON.stringify(row.payload) }</span>
        };

        return (
            <Table
                pagination={ false }
                width="100%"
                dataSource={ this.state.events }
                rowKey="id"
                columns={[
                    { key: 'event', title: 'Recent Events', dataIndex: 'event', render: (text, row) => tag(row)},
                    { key: 'created_at', title: 'Time', dataIndex: 'created_at', render: text => formatDateTime(text) },
                    { key: 'payload', title: 'Payload', dataIndex: 'payload', render: (text, row) =>  payload(row)},
                ]}
            />
        )
    };

    renderReport = () =>{

        if (!this.state.events.length) {
            return <NoData />
        }

        return this.renderEvents()
    };

    renderBusy = () => {
        return <div className="p-6">
            <Spin />
        </div>
    };

    render() {
        const { loading } = this.state;

        return (
            <div>
                <Card
                    title="Scheduled Task Events"
                    extra={ this.renderFilter() }
                    bodyStyle={{ padding: 0, borderTop: '1px solid #e8e8e8', borderBottom: 0 }}
                >
                    { !loading ? this.renderReport() : this.renderBusy() }
                </Card>
            </div>
        )
    }
}
