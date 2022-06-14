import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RecomndedComponent } from './recomnded.component';

describe('RecomndedComponent', () => {
  let component: RecomndedComponent;
  let fixture: ComponentFixture<RecomndedComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RecomndedComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RecomndedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
