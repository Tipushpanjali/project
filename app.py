from flask import Flask, render_template, request, redirect, url_for
from models import db, Event
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///events.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db.init_app(app)

@app.before_first_request
def create_tables():
    db.create_all()

@app.route('/')
def index():
    events = Event.query.all()
    return render_template('index.html', events=events)

@app.route('/add', methods=['GET', 'POST'])
def add_event():
    if request.method == 'POST':
        title = request.form['title']
        description = request.form['description']
        date = request.form['date']
        time = request.form['time']
        location = request.form['location']

        new_event = Event(title=title, description=description, date=date, time=time, location=location)
        db.session.add(new_event)
        db.session.commit()

        return redirect(url_for('index'))

    return render_template('add_event.html')

@app.route('/edit/<int:id>', methods=['GET', 'POST'])
def edit_event(id):
    event = Event.query.get_or_404(id)
    if request.method == 'POST':
        event.title = request.form['title']
        event.description = request.form['description']
        event.date = request.form['date']
        event.time = request.form['time']
        event.location = request.form['location']

        db.session.commit()
        return redirect(url_for('index'))

    return render_template('edit_event.html', event=event)

@app.route('/delete/<int:id>')
def delete_event(id):
    event = Event.query.get_or_404(id)
    db.session.delete(event)
    db.session.commit()
    return redirect(url_for('index'))

if __name__ == '__main__':
    app.run(debug=True)
