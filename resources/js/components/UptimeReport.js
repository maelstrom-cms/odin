import React from 'react';
import { Card, Button, Spin, Progress, Icon, Table, Tag } from 'antd';
import { Line } from 'react-chartjs-2';
import { formatDateTime, GREEN, YELLOW, RED, NoData } from '../helpers';

export default class UptimeReport extends React.Component {

    state = {
        website: JSON.parse(this.props.website),
        loading: true,
        uptime: {
            total: 100,
            month: '-',
            week: '-',
            day: '-',
        },
        response_time: '-',
        response_times: [],
        online: true,
        online_time: '-',
        last_incident: '-',
        events: [],
    };

    componentDidMount() {
        this.update();
    }

    update = async(refresh = false) => {
        await this.setState({
            loading: true
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
        return this.update(true);
    };

    renderUptime() {
        return (
            <>
                <div>
                    <h4>
                        <Icon style={{ marginRight: 10 }} type="api" /> Total Uptime ({ this.state.uptime.total }%)
                    </h4>
                </div>
                <div className="mt-5">
                    <Progress strokeWidth={ 10 } percent={ this.state.uptime.total } showInfo={ false } />
                </div>
            </>
        )
    }

    renderResponseTime() {
        const { response_times } = this.state;

        response_times.reverse();

        const labels = response_times.map( i => i.date );
        const data = response_times.map( i => i.value );

        return (
            <>
                <div>
                    <h4>
                        <Icon style={{ marginRight: 10 }} type="stock" /> Response Time ({ this.state.response_time }ms)
                    </h4>
                </div>
                <div className="mt-5">
                    <Line
                        height={ 120 }
                        options={{
                            legend: {
                                display: false,
                            },
                            scales: {
                                xAxes: [{ display: false }],
                                yAxes: [{ ticks: {
                                    suggestedMax: (Math.max(...data)) + 1,
                                    suggestedMin: 0
                                }}],
                            },
                        }}
                        data={{
                            labels,
                            datasets: [{
                                data,
                                backgroundColor: 'rgba(114, 192, 64, .2)',
                                borderColor: 'rgba(114, 192, 64, 1)',
                                pointColor: 'rgba(114, 192, 64, 1)',
                            }]
                        }}
                    />
                </div>
            </>
        )
    }

    renderCurrentStatus() {
        const { online, online_time } = this.state;

        return (
            <div>
                <div>
                    <h4>
                        <Icon style={{ marginRight: 10 }} type="alert" /> Current Status
                    </h4>
                </div>
                <div className="mt-5 flex items-center">
                    <div className="w-1/3">
                        <Progress
                            width={ 80 }
                            type="circle"
                            percent={ 100 }
                            status={ online ? 'success' : 'exception' }
                            format={ () => <span className="font-bold">{ online ? 'Up' : 'Down' }</span> }
                        />
                    </div>
                    <div className="w-2/3">
                        <h4>Has been { online ? 'online' : 'offline' } for a total of:</h4>
                        <p className="m-0">{ online_time }</p>
                    </div>
                </div>
            </div>
        )
    }

    renderPeriods() {
        const { uptime } = this.state;

        const color = function (uptime) {
            if (uptime >= 90) {
                return GREEN;
            }

            if (uptime <= 50) {
                return RED
            }

            return YELLOW;
        };

        const icon = function (uptime) {
            if (uptime >= 90) {
                return 'like';
            }

            if (uptime <= 50) {
                return 'dislike'
            }

            return 'meh'
        };

        return (
            <div>
                <div>
                    <h4>
                        <Icon style={{ marginRight: 10 }} type="api" /> Uptime
                    </h4>
                </div>
                <div className="mt-5">
                    <div className="mb-2"><Icon type={ icon(uptime.day) } style={{ color: color(uptime.day) }} /> <strong className="text-base" style={{ color: color(uptime.day) }}>{ uptime.day }%</strong> (last 24 hours)</div>
                    <div className="mb-2"><Icon type={ icon(uptime.week) } style={{ color: color(uptime.week) }} /> <strong className="text-base" style={{ color: color(uptime.week) }}>{ uptime.week }%</strong> (last 7 days)</div>
                    <div className="mb-2"><Icon type={ icon(uptime.month) } style={{ color: color(uptime.month) }} /> <strong className="text-base" style={{ color: color(uptime.month) }}>{ uptime.month }%</strong> (last 30 days)</div>
                </div>
            </div>
        )
    }

    renderLastIncident() {
        return (
            <div>
                <div>
                    <h4>
                        <Icon style={{ marginRight: 10 }} type="fire" /> Last Downtime
                    </h4>
                </div>
                <div className="mt-2" style={{ color: RED }}>
                    <p>{ this.state.last_incident }</p>
                </div>
            </div>
        )
    };

    renderEvents() {

        const tag = function (row) {
            const color = row.state === 'up' ? 'green' : 'red';
            const icon = row.state === 'up' ? 'arrow-up' : 'arrow-down';

            return <Tag color={ color }>
                <Icon type={ icon } style={{ marginRight: 5 }} />
                { row.state }
            </Tag>
        };

        const reason = function (row) {
            const color = row.state === 'up' ? GREEN : RED;

            return <span style={{ color }}>{ row.reason }</span>
        };

        return (
            <div className="mt-5">
                <Table
                    pagination={ false }
                    width="100%"
                    dataSource={ this.state.events }
                    rowKey="date"
                    columns={[
                        { key: 'event', title: 'Recent Events', dataIndex: 'state', render: (text, row) => tag(row)},
                        { key: 'date', title: 'Time', dataIndex: 'date', render: text => formatDateTime(text) },
                        { key: 'reason', title: 'Reason', dataIndex: 'reason', render: (text, row) =>  reason(row)},
                        { key: 'duration', title: 'Duration', dataIndex: 'duration' },
                    ]}
                />
            </div>
        )
    };

    renderReport = () =>{

        if (!this.state.events.length) {
            return <NoData />
        }

        return (
            <div className="flex flex-wrap">
                <div className="w-2/3 pr-8">
                    <div className="mb-6">{ this.renderUptime() }</div>
                    <div className="mb-6">{ this.renderResponseTime() }</div>
                </div>
                <div className="w-1/3">
                    <div className="mb-6">{ this.renderCurrentStatus() }</div>
                    <div className="mb-6">{ this.renderPeriods() }</div>
                    <div>{ this.renderLastIncident() }</div>
                </div>
                <div className="w-full">
                    { this.renderEvents() }
                </div>
            </div>
        )
    };

    renderBusy = () => {
        const { now, previous } = this.state;

        if (now && previous) {
            return this.renderReport()
        }

        return <Spin />
    };

    render() {
        const { loading } = this.state;

        return (
            <div>
                <Card
                    title="Uptime Monitor"
                    extra={ <Button loading={ loading } onClick={ this.forceUpdate }>Refresh</Button> }
                >
                    { !loading ? this.renderReport() : this.renderBusy() }
                </Card>
            </div>
        )
    }
}
